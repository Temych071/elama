<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Billing\Payments\Actions\DispatchAutoRefillAction;
use Module\Billing\Payments\Actions\RunAutoRefillAction;
use Module\User\Models\User;

class RunAutoRefillCommand extends Command
{
    // Добавлял, чтобы удобнее проверять было, но вдруг пригодится)

    protected $signature = 'user:auto-refill {user} {--dispatch}';

    public function handle(): void
    {
        /** @var ?User $user */
        $user = User::query()->find($this->argument('user'));

        if ($user === null) {
            $this->error('Пользователь с указанным индексом не найден.');
            return;
        }

        if ($this->option('dispatch')) {
            app(DispatchAutoRefillAction::class)->execute($user);
            $this->info('Задача на вызов авто-пополнения создана.');
        } else {
            $this->info(
                app(RunAutoRefillAction::class)->execute($user)
                    ? 'Лимит авто-пополнения достигнут, платёж создан.'
                    : 'Лимит авто-пополнения не достигнут или авто-пополнение выключено.'
            );
        }
    }
}
