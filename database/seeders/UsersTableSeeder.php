<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create([
            'name' => 'Usuário Teste',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user123')
        ]);
    }
}
