<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Campaign\Models\Campaign;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = \Module\User\Models\User::factory()->create([
            'email' => 'admin@admin.com',
        ]);

        Campaign::factory(5)->create();
    }
}
