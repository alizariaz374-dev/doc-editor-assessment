<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::firstOrCreate(
        ['email' => 'test@example.com'],
        ['name' => 'Alice Owner', 'password' => bcrypt('password')]
    );

    \App\Models\User::firstOrCreate(
        ['email' => 'marge.mosciski@example.org'],
        ['name' => 'Bob Shared', 'password' => bcrypt('password')]
    );
}
}
