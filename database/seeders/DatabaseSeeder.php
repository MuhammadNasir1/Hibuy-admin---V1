<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'user_name' => 'Admin',
            'user_email' => 'admin@gmail.com',
            'user_password' => Hash::make('12345678'),
            'user_role' => 'admin',
        ]);
        $this->call([
            CourierSeeder::class, // Add this line
        ]);
    }
}
