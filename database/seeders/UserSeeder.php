<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@base-app.com'],
            [
                'name' => 'Utilizador de Teste',
                'password' => Hash::make('password'),
            ]
        );
    }
}
