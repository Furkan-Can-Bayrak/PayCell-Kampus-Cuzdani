<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $merchants = Merchant::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        foreach ($users as $user) {
            // Her kullanıcı için çeşitli transaction'lar oluştur
            $this->createTransactionsForUser($user, $merchants);
        }
    }

    private function createTransactionsForUser(User $user, $merchants)
    {
        $baseDate = Carbon::now()->subDays(30);
        
        // 1. Para yükleme işlemleri (topup)
        for ($i = 0; $i < rand(2, 5); $i++) {
            $amount = rand(50, 500);
            $date = $baseDate->copy()->addDays(rand(0, 25));
            
            Transaction::create([
                'user_id' => $user->id,
                'merchant_id' => null,
                'amount' => $amount,
                'currency' => 'TRY',
                'type' => Transaction::TYPE_TOPUP,
                'meta' => [
                    'method' => 'wallet_topup',
                    'previous_balance' => rand(0, 100),
                    'new_balance' => rand(100, 600),
                    'topup_source' => rand(0, 1) ? 'credit_card' : 'bank_transfer',
                ],
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // 2. Merchant ödemeleri (payment)
        if ($merchants->isNotEmpty()) {
            for ($i = 0; $i < rand(3, 8); $i++) {
                $merchant = $merchants->random();
                $amount = rand(10, 150);
                $date = $baseDate->copy()->addDays(rand(5, 30));
                
                Transaction::create([
                    'user_id' => $user->id,
                    'merchant_id' => $merchant->id,
                    'amount' => $amount,
                    'currency' => 'TRY',
                    'type' => Transaction::TYPE_PAYMENT,
                    'meta' => [
                        'qr_id' => 'QR-' . rand(10000, 99999),
                        'payment_method' => 'qr_code',
                        'previous_balance' => rand(50, 300),
                        'new_balance' => rand(20, 250),
                        'merchant_category' => $merchant->category->name,
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        // 3. Para transfer işlemleri (transfer_out ve transfer_in)
        $otherUsers = User::where('id', '!=', $user->id)->get();
        if ($otherUsers->isNotEmpty()) {
            // Transfer out (para gönderme)
            for ($i = 0; $i < rand(1, 4); $i++) {
                $recipient = $otherUsers->random();
                $amount = rand(20, 100);
                $date = $baseDate->copy()->addDays(rand(10, 28));
                
                // Gönderen için transfer_out
                Transaction::create([
                    'user_id' => $user->id,
                    'merchant_id' => null,
                    'amount' => $amount,
                    'currency' => 'TRY',
                    'type' => Transaction::TYPE_TRANSFER_OUT,
                    'meta' => [
                        'recipient_count' => 1,
                        'amount_per_recipient' => $amount,
                        'recipient_ids' => [$recipient->id],
                        'recipient_name' => $recipient->name . ' ' . $recipient->surname,
                        'transfer_note' => $this->getRandomTransferNote(),
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Alıcı için transfer_in
                Transaction::create([
                    'user_id' => $recipient->id,
                    'merchant_id' => null,
                    'amount' => $amount,
                    'currency' => 'TRY',
                    'type' => Transaction::TYPE_TRANSFER_IN,
                    'meta' => [
                        'sender_id' => $user->id,
                        'sender_name' => $user->name . ' ' . $user->surname,
                        'transfer_type' => 'friend_transfer',
                        'transfer_note' => $this->getRandomTransferNote(),
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        // 4. Cashback işlemleri (nadiren)
        if (rand(0, 100) < 30) { // %30 şans
            for ($i = 0; $i < rand(1, 2); $i++) {
                $amount = rand(5, 25);
                $date = $baseDate->copy()->addDays(rand(15, 30));
                
                Transaction::create([
                    'user_id' => $user->id,
                    'merchant_id' => $merchants->isNotEmpty() ? $merchants->random()->id : null,
                    'amount' => $amount,
                    'currency' => 'TRY',
                    'type' => Transaction::TYPE_CASHBACK,
                    'meta' => [
                        'cashback_percentage' => rand(1, 10),
                        'original_transaction_amount' => $amount * rand(5, 20),
                        'cashback_reason' => $this->getRandomCashbackReason(),
                        'campaign_id' => 'CAMP-' . rand(1000, 9999),
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }

    private function getRandomTransferNote(): string
    {
        $notes = [
            'Yemek parası',
            'Borç ödeme',
            'Hediye',
            'Ortak masraf',
            'Kitap parası',
            'Ulaşım ücreti',
            'Kahve parası',
            'Proje ödemesi',
            'Doğum günü hediyesi',
            'Acil durum',
        ];

        return $notes[array_rand($notes)];
    }

    private function getRandomCashbackReason(): string
    {
        $reasons = [
            'İlk alışveriş bonusu',
            'Sadakat programı',
            'Kampanya cashback',
            'Referans bonusu',
            'Haftalık cashback',
            'Özel gün bonusu',
            'Yüksek harcama bonusu',
            'Kategori cashback',
        ];

        return $reasons[array_rand($reasons)];
    }
}
