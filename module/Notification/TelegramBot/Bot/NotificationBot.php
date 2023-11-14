<?php

namespace Module\Notification\TelegramBot\Bot;

use Exception;
use Longman\TelegramBot\Telegram;
use Module\Notification\Models\TelegramNotificationModel;

class NotificationBot extends Telegram
{
    /**
     * @throws Exception
     */
    public function generateAndRegisterUniqueCode(string $chat_id): array|string
    {
        if ($this->userHasValidCode($chat_id)) {
            $model = TelegramNotificationModel::query()->where('chat_id', $chat_id)->firstOrFail();
            return $model->code;
        }
        $randomCode = random_int(100000, 999999);
        $model = $this->registerCode(000000, $chat_id);
        $idLength = strlen((string)$model->id);

        $uniqueCode = substr_replace((string)$randomCode, (string)$model->id, 0, $idLength);
        $model->update(['code' => $uniqueCode]);

        return $uniqueCode;
    }

    private function userHasValidCode(string $chat_id): bool
    {
        $model = TelegramNotificationModel::whereChatId($chat_id)->first();
        return !($model === null || !$model->exists() || now()->isAfter($model->expired_at));
    }

    private function registerCode(int $code, string $chat_id)
    {
        $model = TelegramNotificationModel::query()->where('chat_id', $chat_id)->first();
        if ($model !== null) {
            return $model;
        }

        $expired_at = now()->addWeek();

        return TelegramNotificationModel::query()
            ->create(['chat_id' => $chat_id, 'code' => $code, 'expired_at' => $expired_at]);
    }

    public function updateCode(string $code, string $chat_id): bool
    {
        $model = TelegramNotificationModel::query()->where('chat_id', $chat_id)->first();
        if ($model === null) {
            return false;
        }

        $expired_at = now()->addWeek();
        return $model->update(['expired_at' => $expired_at, 'code' => $code]);
    }
}
