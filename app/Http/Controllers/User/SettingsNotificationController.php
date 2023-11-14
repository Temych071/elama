<?php

namespace App\Http\Controllers\User;

use App\Exceptions\BusinessException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;
use Module\Notification\Enums\NotificationSourceTypes;
use Module\Notification\Models\TelegramNotificationModel;
use Module\Notification\TelegramNotificationService;

class SettingsNotificationController
{
    public function show(Request $request): Response|ResponseFactory
    {
        $user = $request->user();
        $enableTelegram = $user->hasEnabledNotificationType(NotificationSourceTypes::Telegram);
        $codeTelegram = TelegramNotificationModel::query()->where('user_id', $user->id)->first()?->code;

        $enableEmail = $user->hasEnabledNotificationType(NotificationSourceTypes::Mail);
        $emailAddress = $user->notification_email ?? $user->email;

        return inertia('Settings/SettingsNotification', [
            'enableTelegram' => $enableTelegram,
            'codeTelegram' => $codeTelegram,
            'enableEmail' => $enableEmail,
            'emailAddress' => $emailAddress,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function setEmailNotifications(Request $request): RedirectResponse
    {
        $validated = Validator::make($request->all(), [
            'emailNotifications' => ['required', 'boolean', 'accepted'],
            'emailAddress' => ['required', 'email'],
        ])->validate();

        $user = $request->user();
        $user->updateNotificationTypes(NotificationSourceTypes::Mail);

        $user->update(['notification_email' => $validated['emailAddress']]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Успешно.',
        ]);
    }

    /**
     * @throws BusinessException
     */
    public function unsetEmailNotifications(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $user->removeNotificationType(NotificationSourceTypes::Mail);
            $user->update(['notification_email' => null]);

            return redirect()->route('user.settings_notifications.show')->with('toast', [
                'type' => 'success',
                'message' => 'Уведомления на почту отключены.',
            ]);
        } catch (\Exception $e) {
            throw new BusinessException($e->getMessage() ?? "Произошла ошибка при отключении уведомлений");
        }
    }

    /**
     * @throws ValidationException
     */
    public function setTelegramNotifications(Request $request): RedirectResponse
    {
        Validator::make(
            $request->all(),
            [
            'telegramNotification' => ['required', 'boolean', 'accepted'],
            'telegramCode' => ['required', 'exists:telegram_notification,code']
            ],
            [],
            ['telegramCode' => 'код'],
        )->validate();

        $user = $request->user();
        $user->updateNotificationTypes(NotificationSourceTypes::Telegram);

        $telegram = TelegramNotificationModel::query()->where('code', $request->get('telegramCode'));
        $telegram->update(['user_id' => $user->id]);

        $telegramService = new TelegramNotificationService();
        $telegramService->sendMessage($user->id, 'Уведомления успешно настроены!');

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Успешно.',
        ]);
    }

    /**
     * @throws BusinessException
     */
    public function unsetTelegramNotifications(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $user->removeNotificationType(NotificationSourceTypes::Telegram);
            $telegram = TelegramNotificationModel::query()->where('user_id', $user->id);
            if (!$telegram->exists()) {
                throw new \RuntimeException('У вас нет подключенных уведомлений в телеграме');
            }
            $telegram->delete();

            return redirect()->route('user.settings_notifications.show')->with('toast', [
                'type' => 'success',
                'message' => 'Уведомления в телеграме отключены.',
            ]);
        } catch (\Exception $e) {
            throw new BusinessException($e->getMessage() ?? "Произошла ошибка при отключении уведомлений");
        }
    }
}
