<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 5)
            ->create()
            ->each(function ($user, $index) {
                $user->update([
                    "name"      => "Test User #{$index}",
                    "email"     => "test{$index}@example.com"
                ]);
            });
    }
}
