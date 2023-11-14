<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\User\Enums\UserRole;
use Module\User\Models\User;

class AssignUserRoleCommand extends Command
{
    protected $signature = 'user:assign-role {user}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $user = User::findOrFail($this->argument('user'));

        $role = $this->anticipate('Select a role', array_map(fn ($role): string => $role->value, UserRole::cases()));

        $user->role = UserRole::from($role);

        if ($user->save()) {
            $userName = $user->name;
            $roleName = $user->role->value;
            $this->info("$userName role is $roleName");
        }
    }
}
