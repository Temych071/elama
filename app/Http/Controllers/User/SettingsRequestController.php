<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Module\User\Models\SettingsRequest;
use Module\User\Models\User;
use Module\User\Notifications\NewSettingsRequestNotification;

final class SettingsRequestController
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

//        if ($user->settingsRequests()) {
//            return redirect()->back()->with('toast', [
//                'type' => 'warning',
//                'message' => 'Вы уже отправляли заявку на настройку проекта.'
//            ]);
//        }

        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D/', '', (string) $data['phone']);
        }

        $userCampaignIds = $user->campaigns->map->id;
        $data = validator($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'campaign_id' => [
                'required',
                'numeric',
                Rule::exists('campaigns', 'id')
                    ->whereIn('id', $userCampaignIds)
            ],
        ])->validate();

        /** @var SettingsRequest $settingsRequest */
        $settingsRequest = $user->settingsRequests()->create($data);

        if (!is_null($notifyTo = config('mail.settings_request_notification_email'))) {
            Notification::route('mail', $notifyTo)
                ->notify(new NewSettingsRequestNotification($settingsRequest));
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => trans('toasts.settingsRequest.success'),
        ]);
    }
}
