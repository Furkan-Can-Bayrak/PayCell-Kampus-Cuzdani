<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = [
            // Yemek & İçecek
            'Yemek & İçecek' => [
                'McDonald\'s',
                'Burger King',
                'KFC',
                'Pizza Hut',
                'Starbucks',
                'Kahve Dünyası',
                'Domino\'s Pizza',
                'Subway',
                'Popeyes',
                'Taco Bell',
            ],
            // Alışveriş
            'Alışveriş' => [
                'Migros',
                'Carrefour',
                'A101',
                'BIM',
                'Teknosa',
                'MediaMarkt',
                'H&M',
                'Zara',
                'LC Waikiki',
                'DeFacto',
            ],
            // Ulaşım
            'Ulaşım' => [
                'Uber',
                'BiTaksi',
                'Taksi',
                'Metro',
                'Otobüs',
                'Dolmuş',
                'Tren',
                'Uçak',
                'Feribot',
                'Bisiklet',
            ],
            // Eğlence
            'Eğlence' => [
                'Sinema',
                'Tiyatro',
                'Konser',
                'Bar',
                'Disko',
                'Kafe',
                'Oyun Salonu',
                'Bowling',
                'Bilardo',
                'Escape Room',
            ],
            // Sağlık
            'Sağlık' => [
                'Eczane',
                'Hastane',
                'Doktor',
                'Diş Hekimi',
                'Göz Doktoru',
                'Fizyoterapist',
                'Psikolog',
                'Laboratuvar',
                'Radyoloji',
                'Ambulans',
            ],
        ];

        foreach ($merchants as $categoryName => $merchantNames) {
            $category = Category::where('name', $categoryName)->first();
            
            if ($category) {
                foreach ($merchantNames as $merchantName) {
                    Merchant::firstOrCreate([
                        'name' => $merchantName,
                        'category_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
