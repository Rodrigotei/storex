<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Rodrigo Teixeira', 
            'email' => 'rodrigo@gmail.com', 
            'password' => bcrypt('123'), 
            'phone' => '79996820727', 
            'document' => '08144645561', 
            'name_store' => 'rt lanchonete', 
            'slug' => 'rt-lanchonete'
            ]
        );
    }
}
