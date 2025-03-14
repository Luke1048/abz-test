<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\User;
use App\Models\Token;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Position::factory()
            ->count(4)
            ->create();

        User::factory()
            ->count(45)
            ->create();

        Token::factory()->create();
    }
}
