<?php

namespace Module\Notification;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Module\Notification\Models\TelegramNotificationModel;
use Module\Notification\TelegramBot\Bot\NotificationBot;

class TelegramNotificationService
{
    public readonly NotificationBot $bot;

    // https://laravel-notification-channels.com/telegram/#usage

    public function __construct()
    {
        $this->bot = new NotificationBot(
            config('telegram.api_key'),
            config('telegram.bot_username'),
        );
    }

    public function sendMessage(string|int $userId, string $message, $file = null): ServerResponse
    {
        $telegram = $this->findNotificationModelByUserId($userId);
        return $this->sendBotMessage($telegram->chat_id, $message, $file);
    }

    public function sendData(string|int $userId, array $data): ServerResponse
    {
        $telegram = $this->findNotificationModelByUserId($userId);

        // $text = $data['textHTML'] ?? $data['title'] . PHP_EOL . $data['text'] ?? '';
        // Было ^так^... Либо я чего-то не понял, либо скобок всё же не хватало)

        if ($data['tgBoldTitle']) {
            $data['title'] = '<b>'.$data['title'].'</b>';
        }

        $text = $data['textHTML'] ?? ($data['title'] . PHP_EOL . $data['text']) ?? '';

        // Костыль, но вроде работает норм)
        // Вообще странно, что <br> не парсится нормально
        $text = preg_replace('/<br\/?>/i', PHP_EOL, $text);

        return $this->sendBotMessage($telegram->chat_id, $text, $data['file'] ?? null);
    }

    /**
     * @throws TelegramException
     */
    private function sendBotMessage(
        string $chat_id,
        string $message,
        $pathToFile = null,
        string $parse_mode = 'HTML',
    ): ServerResponse {
        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => $parse_mode
        ];

        if ($pathToFile === null) {
            $res = Request::sendMessage($data);
        } else {
            $res = Request::sendDocument([...$data, 'document' => null]);
        }

        return $res;
    }

    private function findNotificationModelByUserId(string|int $user_id)
    {
        return TelegramNotificationModel::whereUserId($user_id)->firstOrFail();
    }

    private function handle(): void
    {
        $this->bot->handle();
    }
}
