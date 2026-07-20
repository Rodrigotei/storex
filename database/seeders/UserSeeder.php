<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'teste@storex.local'],
            [
                'name' => 'Usuário de Teste',
                'document' => '00000000000',
                'password' => 'StoreX@123',
                'status' => 'active',
                'expires_at' => now()->addYear(),
            ],
        );

        $user->store()->updateOrCreate(
            [],
            [
                'name' => 'Loja Demonstração',
                'slug' => 'loja-teste',
                'phone' => '79999999999',
                'description' => 'Catálogo criado para testes locais do StoreX.',
                'delivery_fee' => 5,
            ],
        );
    }
}
