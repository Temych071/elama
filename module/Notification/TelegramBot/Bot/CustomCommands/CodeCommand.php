<?php

namespace Module\Notification\TelegramBot\Bot\CustomCommands;

use Longman\TelegramBot\Entities\ServerResponse;
use Module\Notification\TelegramBot\Bot\NotificationBotCommand;

class CodeCommand extends NotificationBotCommand
{
    /**
     * @var string
     */
    protected $name = 'code';

    /**
     * @var string
     */
    protected $description = 'Получить код идентификации';

    /**
     * @var string
     */
    protected $usage = '/code';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    public function execute(): ServerResponse
    {
        try {
            $chat_id = $this->getChatId();
            $this->setChatId($chat_id);

            $chat_id = $this->getChatId();
            $this->setChatId($chat_id);

            return $this->replyToChat(
                'Ваш код:  <code>'. $this->getTelegram()->generateAndRegisterUniqueCode($chat_id). '</code>',
                ['parse_mode' => 'HTML']
            );
        } catch (\Exception $exception) {
            return $this->replyToChat('Произошла ошибка, повторите позже.' . PHP_EOL . $exception->getMessage());
        }
    }
}
