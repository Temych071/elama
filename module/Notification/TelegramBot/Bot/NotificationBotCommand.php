<?php

namespace Module\Notification\TelegramBot\Bot;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

abstract class NotificationBotCommand extends UserCommand
{
    /** @var int|null predefined chat it to reply to */
    protected $chat_id;

    public function __construct(Telegram $telegram, Update $update = null)
    {
        parent::__construct($telegram, $update);
    }

    public function setChatId(?int $chat_id): NotificationBotCommand
    {
        $this->chat_id = $chat_id;
        return $this;
    }

    public function getChatId(): int
    {
        if (is_null($this->chat_id)) {
            $msg = $this->getMessage() ?: $this->getChannelPost();
            return $msg->getChat()->getId();
        }

        return $this->chat_id;
    }

    /**
     * A shortcut to just send back some text to whoever the message came from.
     *
     * @param $text
     * @return ServerResponse
     * @throws TelegramException
     */
    public function replyWithText($text): ServerResponse
    {
        return Request::sendMessage([
            'chat_id' => $this->getChatId(),
            'text' => $text
        ]);
    }

    /**
     * @throws TelegramException
     */
    public function replyWithMarkdown(string $text): ServerResponse
    {
        $chat_id = $this->getChatId();

        $data = [
            'chat_id' => $chat_id,
            'parse_mode' => 'markdown',
            'text' => str_replace('_', "\\_", $text)
        ];

        return Request::sendMessage($data);
    }
}
