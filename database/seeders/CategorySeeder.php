<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Kafe & Restoran',
            'Market & Gıda',
            'Alışveriş',
            'Ulaşım',
            'Eğlence',
            'Sağlık',
            'Eğitim',
            'Teknoloji',
            'Moda & Giyim',
            'Ev & Yaşam',
            'Spor & Fitness',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }
    }
}
