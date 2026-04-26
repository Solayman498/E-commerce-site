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
        // ডামি প্রোডাক্ট ১
        Product::create([
            'name' => 'Premium Dog Food',
            'slug' => Str::slug('Premium Dog Food'),
            'description' => 'Nutritious and delicious food for your dogs.',
            'price' => 1500.00,
            'image' => 'dog-food.png', // আপনার public/storage ফোল্ডারে থাকা ছবির নাম
            'category' => 'Dog',
            'stock' => 20,
            'is_featured' => true,
        ]);

        // ডামি প্রোডাক্ট ২
        Product::create([
            'name' => 'Interactive Cat Toy',
            'slug' => Str::slug('Interactive Cat Toy'),
            'description' => 'Keep your cat active with this fun toy.',
            'price' => 450.00,
            'image' => 'cat-toy.png',
            'category' => 'Cat',
            'stock' => 50,
            'is_featured' => true,
        ]);

        // ডামি প্রোডাক্ট ৩
        Product::create([
            'name' => 'Bird Seed Mix',
            'slug' => Str::slug('Bird Seed Mix'),
            'description' => 'Healthy seeds for all types of birds.',
            'price' => 300.00,
            'image' => 'bird-seed.png',
            'category' => 'Birds',
            'stock' => 100,
            'is_featured' => false,
        ]);

        echo "Products seeded successfully! \n";
    }
}