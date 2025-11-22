<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\GejalaSeeder;
use Database\Seeders\PenyakitSeeder;
use Database\Seeders\PenyakitGejalaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Mufti Restu Mahesa',
            'username' => 'restumahesa',
            'email' => 'mufti.restumahesa@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        User::create([
            'nama' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $this->call([
            GejalaSeeder::class,
            PenyakitSeeder::class,
            PenyakitGejalaSeeder::class,
        ]);
    }
}
