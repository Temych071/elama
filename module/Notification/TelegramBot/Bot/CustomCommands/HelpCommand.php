<?php

namespace Module\Notification\TelegramBot\Bot\CustomCommands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Module\Notification\TelegramBot\Bot\NotificationBotCommand;

class HelpCommand extends NotificationBotCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'Помощь';

    /**
     * @var string
     */
    protected $usage = '/help or /help <command>';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @inheritdoc
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $command_str = trim($message->getText(true));

        // Admin commands shouldn't be shown in group chats
        $safe_to_show = $message->getChat()->isPrivateChat();

        $data = [
            'chat_id'    => $chat_id,
            'parse_mode' => 'markdown',
            'reply_markup' => json_encode(['remove_keyboard' => true]),
        ];

        [$all_commands, $user_commands, $admin_commands] = $this->getUserAdminCommands();

        // If no command parameter is passed, show the list.
        if ($command_str === '') {
            $data['text'] = '*Список доступных команд*:' . PHP_EOL;
            foreach ($user_commands as $user_command) {
                $data['text'] .= '/' . $user_command->getName() . ' - ' . $user_command->getDescription() . PHP_EOL;
            }

            if ($safe_to_show && $admin_commands !== []) {
                $data['text'] .= PHP_EOL . '*Admin Commands List*:' . PHP_EOL;
                foreach ($admin_commands as $admin_command) {
                    $data['text'] .= '/' . $admin_command->getName() . ' - ' . $admin_command->getDescription() . PHP_EOL;
                }
            }

            $data['text'] .= PHP_EOL . 'Для получения информации о команде: /help <command>';

            return Request::sendMessage($data);
        }

        $command_str = str_replace('/', '', $command_str);
        if (isset($all_commands[$command_str]) && ($safe_to_show || !$all_commands[$command_str]->isAdminCommand())) {
            $command      = $all_commands[$command_str];
            $data['text'] = sprintf(
                'Command: %s (v%s)' . PHP_EOL .
                'Description: %s' . PHP_EOL .
                'Usage: %s',
                $command->getName(),
                $command->getVersion(),
                $command->getDescription(),
                $command->getUsage()
            );

            return Request::sendMessage($data);
        }

        $data['text'] = 'Команда /' . $command_str . ' не найдена';

        return Request::sendMessage($data);
    }

    /**
     * Get all available User and Admin commands to display in the help list.
     *
     * @return Command[][]
     * @throws TelegramException
     */
    protected function getUserAdminCommands(): array
    {
        // Only get enabled Admin and User commands that are allowed to be shown.
        /** @var Command[] $commands */
        $commands = array_filter($this->telegram->getCommandsList(), static fn($command): bool => /** @var Command $command */
!$command->isSystemCommand() && $command->showInHelp() && $command->isEnabled());

        $user_commands = array_filter($commands, fn($command): bool => /** @var Command $command */
$command->getName() !== 'genericmessage'
            && $command->getName() !== 'start' && $command->getName() !== 'linto');

        $admin_commands = array_filter($commands, fn($command): bool => /** @var Command $command */
$command->isAdminCommand());

        ksort($commands);
        ksort($user_commands);
        ksort($admin_commands);

        return [$commands, $user_commands, $admin_commands];
    }
}
