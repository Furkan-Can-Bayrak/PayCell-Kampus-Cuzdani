<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tüm kullanıcılar için wallet oluştur
        $users = User::all();

        foreach ($users as $user) {
            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 10] // 0-50 TL arası rastgele bakiye
            );
        }
    }
}
