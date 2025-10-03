<?php

namespace Database\Seeders;

use App\Models\Split;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class SplitSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = User::all();
        $transactions = Transaction::where('type', 'payment')->with('merchant')->get();
        
        if ($users->count() < 2 || $transactions->isEmpty()) {
            $this->command->warn('Yeterli kullanıcı veya payment transaction bulunamadı.');
            return;
        }
        
        // Test kullanıcısına gelen split istekleri oluştur
        $testUser = User::where('email', 'test@test.com')->first();
        if (!$testUser) {
            $testUser = $users->first();
        }
        
        // Diğer kullanıcılardan test kullanıcısına split istekleri gönder
        $otherUsers = $users->where('id', '!=', $testUser->id)->take(3);
        
        foreach ($otherUsers as $requester) {
            $transaction = $transactions->where('user_id', $requester->id)->first();
            
            if (!$transaction) {
                // Bu kullanıcının transaction'ı yoksa başka birinin transaction'ını kullan
                $transaction = $transactions->random();
            }
            
            $shareAmount = $transaction->amount / 2; // Yarı yarıya böl
            
            Split::create([
                'transaction_id' => $transaction->id,
                'requester_id' => $requester->id,
                'user_id' => $testUser->id,
                'weight' => 1.00,
                'share_amount' => $shareAmount,
                'status' => Split::STATUS_PENDING,
                'meta' => [
                    'share_type' => 'equal',
                    'friend_name' => $testUser->name . ' ' . $testUser->surname,
                    'merchant_name' => $transaction->merchant->name ?? 'Test Merchant',
                    'original_amount' => $transaction->amount,
                    'created_at' => now()->subMinutes(rand(5, 60))->toISOString(),
                ],
            ]);
        }
        
        $this->command->info('Split test verileri oluşturuldu.');
    }
}