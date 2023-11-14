<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Module\Notification\TelegramNotificationService;

class TelegramChannel
{
    public function send($notifiable, Notification $notification): bool
    {
//        try {
        if (!method_exists($notifiable, 'getTelegramChatId')) {
            return false;
        }

        $chat_id = $notifiable->getTelegramChatId($notifiable);
        if ($chat_id === null) {
            return false;
        }

        $data = $notification->toArray($notifiable);
        if (empty($data)) {
            return false;
        }

        $data['chat_id']  = $chat_id;

        $notifyService = new TelegramNotificationService();
        $notifyService->sendData($notifiable->id, $data);

        return true;
//        } catch (\Exception) {
//            return false;
//        }
    }
}
