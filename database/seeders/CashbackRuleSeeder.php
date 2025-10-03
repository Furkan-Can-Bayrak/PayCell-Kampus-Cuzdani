<?php

namespace Database\Seeders;

use App\Models\CashbackRule;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashbackRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kafe & Restoran kategorisini bul
        $kafeCategory = Category::where('name', 'Kafe & Restoran')->first();
        
        if (!$kafeCategory) {
            $this->command->warn('Kafe & Restoran kategorisi bulunamadı. Önce CategorySeeder çalıştırın.');
            return;
        }

        // 1. Kafe & Restoran kategorisinde %5 iade
        CashbackRule::firstOrCreate(
            [
                'rule_type' => CashbackRule::TYPE_CATEGORY_PERCENTAGE,
                'category_id' => $kafeCategory->id,
            ],
            [
                'name' => 'Kafe & Restoran %5 İade',
                'description' => 'Kafe ve restoran harcamalarınızda %5 para iadesi kazanın',
                'rate' => 0.05, // %5
                'cap' => 50.00, // Maksimum 50 TL iade
                'first_time_only' => false,
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(6), // 6 ay geçerli
            ]
        );

        // 2. İlk QR ödeme 20 TL iade (tek seferlik)
        CashbackRule::firstOrCreate(
            [
                'rule_type' => CashbackRule::TYPE_FIRST_QR_BONUS,
                'first_time_only' => true,
            ],
            [
                'name' => 'İlk QR Ödeme Bonusu',
                'description' => 'İlk QR kod ödemende 20 TL para iadesi kazan!',
                'flat_amount' => 20.00,
                'first_time_only' => true,
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(12), // 1 yıl geçerli
            ]
        );

        // 3. Bonus: Alışveriş kategorisinde %3 iade (örnek)
        $alisverisCategory = Category::where('name', 'Alışveriş')->first();
        if ($alisverisCategory) {
            CashbackRule::firstOrCreate(
                [
                    'rule_type' => CashbackRule::TYPE_CATEGORY_PERCENTAGE,
                    'category_id' => $alisverisCategory->id,
                ],
                [
                    'name' => 'Alışveriş %3 İade',
                    'description' => 'Alışveriş harcamalarınızda %3 para iadesi',
                    'rate' => 0.03, // %3
                    'cap' => 30.00, // Maksimum 30 TL iade
                    'first_time_only' => false,
                    'is_active' => true,
                    'starts_at' => now(),
                    'ends_at' => now()->addMonths(3), // 3 ay geçerli
                ]
            );
        }

        // 4. Ulaşım kategorisinde %2 iade (otobüs, metro vb.)
        $ulasimCategory = Category::where('name', 'Ulaşım')->first();
        if ($ulasimCategory) {
            CashbackRule::firstOrCreate(
                [
                    'rule_type' => CashbackRule::TYPE_CATEGORY_PERCENTAGE,
                    'category_id' => $ulasimCategory->id,
                ],
                [
                    'name' => 'Ulaşım %2 İade',
                    'description' => 'Otobüs, metro, taksi gibi ulaşım harcamalarınızda %2 para iadesi',
                    'rate' => 0.02, // %2
                    'cap' => 20.00, // Maksimum 20 TL iade
                    'first_time_only' => false,
                    'is_active' => true,
                    'starts_at' => now(),
                    'ends_at' => now()->addMonths(6), // 6 ay geçerli
                ]
            );
        }

        $this->command->info('Cashback kuralları oluşturuldu:');
        $this->command->info('- Kafe & Restoran %5 İade (maks. 50 TL)');
        $this->command->info('- İlk QR Ödeme Bonusu (20 TL, tek seferlik)');
        if ($alisverisCategory) {
            $this->command->info('- Alışveriş %3 İade (maks. 30 TL)');
        }
        if ($ulasimCategory) {
            $this->command->info('- Ulaşım %2 İade (maks. 20 TL)');
        }
    }
}
