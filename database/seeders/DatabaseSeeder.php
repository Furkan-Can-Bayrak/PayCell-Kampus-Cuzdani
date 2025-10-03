<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WalletSeeder::class,
            FriendSeeder::class,
            CategorySeeder::class,
            MerchantSeeder::class,
            TransactionSeeder::class,
            SplitSeeder::class,
        ]);
    }
}
