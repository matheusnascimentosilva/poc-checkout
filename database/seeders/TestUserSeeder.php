<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'vendedor@teste.com'], // Garante que não vai duplicar
            [
                'name' => 'Vendedor Teste',
                'email' => 'vendedor@teste.com',
                'password' => Hash::make('senha123'), // Senha de teste
            ]
        );
    }
}
