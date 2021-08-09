<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAttr = [
            'name' => 'test_user',
            'email' => 'testUser@mail.com',
        ];

        User::query()->updateOrCreate(
            $userAttr, array_merge($userAttr, [
                'password' => bcrypt('temp_password'),
                'email_verified_at' => now()
            ])
        );
    }
}
