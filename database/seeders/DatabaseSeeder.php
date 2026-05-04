<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => env('ADMIN_EMAIL', 'admin@petshop.com'), 
            'password' => bcrypt(env('ADMIN_PASSWORD', 'admin1234')), 
            'is_admin' => true,
        ]);
    }
}