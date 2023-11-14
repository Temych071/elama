<?php

namespace Module\Notification\TelegramBot\Bot\CustomCommands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Module\Notification\TelegramBot\Bot\NotificationBotCommand;

class StartCommand extends NotificationBotCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        try {
            $chat_id = $this->getChatId();
            $this->setChatId($chat_id);

            $chat_id = $this->getChatId();
            $this->setChatId($chat_id);

            $route = route('user.settings_notifications.show');

            return $this->replyToChat(
                'Чтобы настроить уведомления, введите код на <a href="' . $route . '"> этой странице </a>' .
                'Код: <code>' . $this->getTelegram()->generateAndRegisterUniqueCode($chat_id) . '</code>',
                ['parse_mode' => 'HTML']
            );
        } catch (\Exception $exception) {
            return $this->replyToChat('Произошла ошибка, повторите позже.' . PHP_EOL . $exception->getMessage());
        }
    }
}
