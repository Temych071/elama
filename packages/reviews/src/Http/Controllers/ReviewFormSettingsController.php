<?php

declare(strict_types=1);

namespace Reviews\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Inertia\Response;
use Module\Campaign\Models\Campaign;
use Module\Notification\Models\TelegramNotificationModel;
use Reviews\Actions\CleanupExternalDataAction;
use Reviews\Actions\CopyReviewFormAction;
use Reviews\Actions\CreateReviewFormAction;
use Reviews\Actions\DispatchExternalReviewsFetchingAction;
use Reviews\Enums\ReviewSource;
use Reviews\Http\Requests\FormSettingsRequest;
use Reviews\Models\ReviewForm;
use Reviews\Parsers\Gis\Actions\DoubleGisCheckPlaceAccessAction;
use Reviews\Parsers\Yandex\Services\YandexReviewsService;

final class ReviewFormSettingsController
{
    public function show(Request $request, Campaign $campaign, ReviewForm $reviewForm): Response|RedirectResponse
    {
        $user = $campaign->owner->first();

        if ($reviewForm->exists) {
            $form = $reviewForm->setHidden([])?->toArray();
            $form['page_settings'] = array_merge([
                'fields' => [
                    'name' => [
                        'show' => true,
                        'required' => true,
                    ],
                    'contact' => [
                        'show' => false,
                        'required' => false,
                    ],
                ],
                'colors' => [
                    'background' => '#F8F8F8',
                    'buttons' => '#FFCF00',
                ],
                'policy' => false,
                'policyLink' => '',
            ], $form['page_settings'] ?? []);
        } elseif ($campaign->reviewForms->isNotEmpty()) {
            return redirect()->route('reviews.private.forms.show', [$campaign, $campaign->reviewForms[0]]);
        }

        return Inertia::render('Reviews/Private/Settings', [
            'project' => $campaign,
            'reviewForm' => $form ?? null,
            'reviewForms' => $campaign->reviewForms,

            ...($user?->exists ? [
                'codeTelegram' => TelegramNotificationModel::query()->where('user_id', $user->id)->first()?->code,
                'emailAddress' => $user->notification_email ?? $user->email,
            ] : [])
        ]);
    }

    /**
     * @throws ToastException
     */
    public function init(Request $request, Campaign $campaign): RedirectResponse
    {
        if (!$campaign->canCreateReviewForm()) {
            throw new ToastException('Ваш текущий тариф не позволяет создавать больше форм.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $reviewForm = app(CreateReviewFormAction::class)->execute($campaign, $data['name']);

        return redirect()->route('reviews.private.forms.show', [
            'campaign' => $campaign,
            'reviewForm' => $reviewForm,
        ])->with('toast', [
            'type' => 'success',
            'message' => 'Форма для отзывов успешно создана.',
        ]);
    }

    /**
     * @throws ToastException
     */
    public function update(FormSettingsRequest $request, Campaign $campaign, ReviewForm $reviewForm): RedirectResponse
    {
        $rateLimiterKey = "reviews.private.form-{$reviewForm->id}.settings.save";
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 1)) {
            throw new ToastException('Обновлять настройки филиала можно не чаще раза в минуту.');
        }

        $reviewForm->fill($request->validated());

        $reviewForm->tryToUploadOrDeleteMedia(
            'logo',
            $request->file('logo'),
            $request->boolean('logo_del'),
        );
        $reviewForm->tryToUploadOrDeleteMedia(
            'banner',
            $request->file('banner'),
            $request->boolean('banner_del'),
        );
        $reviewForm->tryToUploadOrDeleteMedia(
            'thx_banner',
            $request->file('thx_banner'),
            $request->boolean('thx_banner_del'),
        );

        $widget_2gis = $request->input('widget_2gis');
        if ($reviewForm->widget_2gis !== $widget_2gis) {
            if (empty($widget_2gis)) {
                $reviewForm->widget_2gis = null;
                $reviewForm->widget_2gis_access = false;
            } else {
                if (ReviewForm::query()->where('widget_2gis', $widget_2gis)->exists()) {
                    throw new ToastException('Указанная организация в 2GIS уже привязана к другому проекту.');
                }

                $reviewForm->widget_2gis_access = app(DoubleGisCheckPlaceAccessAction::class)->execute($widget_2gis);
                $reviewForm->widget_2gis = $widget_2gis;
            }
        }

        $yandex_company_id = $request->integer('yandex_company_id');
        if ((int) $reviewForm->yandex_company_id !== $yandex_company_id) {
            if (empty($yandex_company_id)) {
                $reviewForm->yandex_company_id = null;
            } else {
                if (ReviewForm::query()->where('yandex_company_id', $yandex_company_id)->exists()) {
                    throw new ToastException('Указанная компания Яндекс.Бизнес уже привязана к другому проекту.');
                }

                $company = app(YandexReviewsService::class)->findCompanyCached($yandex_company_id);
                if ($company === null) {
                    throw new ToastException('Доступ к указанной компании Яндекс.Бизнес должен быть предоставлен на сервисный аккаунт dailygrow@yandex.ru');
                }

                $reviewForm->yandex_company_id = $company->id;
            }
        }

        if ($reviewForm->isDirty('yandex_company_id')) {
            app(CleanupExternalDataAction::class)
                ->execute($reviewForm, ReviewSource::YANDEX);
        }

        if ($reviewForm->isDirty('widget_2gis')) {
            app(CleanupExternalDataAction::class)
                ->execute($reviewForm, ReviewSource::DOUBLE_GIS);
        }

        if (
            $reviewForm->isDirty(['yandex_company_id', 'widget_2gis'])
            && !empty($reviewForm->getReviewSources())
        ) {
            app(DispatchExternalReviewsFetchingAction::class)->execute($reviewForm);
        }

        if (!$reviewForm->save()) {
            throw new ToastException('Не удалось обновить настройки формы.');
        }

        RateLimiter::hit($rateLimiterKey);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Настройки формы успешно обновлены.',
        ]);
    }

    /**
     * @throws ToastException
     */
    public function delete(Campaign $campaign, ReviewForm $reviewForm): RedirectResponse
    {
        if (!$reviewForm->delete()) {
            throw new ToastException('Не удалось удалить форму.');
        }

        return redirect()->route('reviews.private.forms', [$campaign])->with('toast', [
            'type' => 'success',
            'message' => 'Форма успешно удалена.',
        ]);
    }

    /**
     * @throws ToastException
     * @throws BusinessException
     */
    public function copy(Campaign $campaign, ReviewForm $reviewForm): RedirectResponse
    {
        if (!$campaign->canCreateReviewForm()) {
            throw new ToastException('Ваш текущий тариф не позволяет создавать больше форм.');
        }

        $copiedReviewForm = app(CopyReviewFormAction::class)->execute($reviewForm);

        return redirect()->route('reviews.private.forms.show', [$campaign, $copiedReviewForm])->with('toast', [
            'type' => 'success',
            'message' => "Форма $reviewForm->name успешно скопирована.",
        ]);
    }

    public function updateDoubleGisAccess(Campaign $campaign, ReviewForm $reviewForm): RedirectResponse
    {
        $rateLimiterKey = "reviews.private.form-{$reviewForm->id}.settings.update-2gis-access";
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 1)) {
            throw new ToastException('Обновлять доступ к ответам 2GIS можно не чаще раза в минуту');
        }
        RateLimiter::hit($rateLimiterKey);

        if (empty($reviewForm->widget_2gis)) {
            throw new ToastException('К данному филиалу не подключена организация в 2GIS');
        }

        if ($reviewForm->widget_2gis_access) {
            throw new ToastException('Доступ к ответам в 2GIS уже был предоставлен.');
        }

        $isBusy = ReviewForm::query()
            ->where('widget_2gis_access', true)
            ->where('widget_2gis', $reviewForm->widget_2gis)
            ->whereNot('id', $reviewForm->id)
            ->exists();

        if ($isBusy) {
            throw new ToastException('Указанная организация в 2GIS уже привязана к другому проекту.');
        }

        $hasAccess = app(DoubleGisCheckPlaceAccessAction::class)->execute($reviewForm->widget_2gis);
        if (!$hasAccess) {
            throw new ToastException('Доступ к указанной организации в 2GIS должен быть предоставлен на сервисный аккаунт dailygrow@yandex.ru');
        }

        $reviewForm->widget_2gis_access = true;
        $reviewForm->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Доступ к ответам в 2GIS предоставлен.',
        ]);
    }
}
