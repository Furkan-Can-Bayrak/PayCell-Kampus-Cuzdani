<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test kullanıcısı (zaten varsa oluşturma)
        User::firstOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Test',
                'surname' => 'User',
                'phone' => '05551234567',
                'password' => Hash::make('1'),
                'email_verified_at' => now(),
            ]
        );

        // Gerçek insan verisi gibi fake kullanıcılar
        $users = [
            [
                'name' => 'Ahmet',
                'surname' => 'Yılmaz',
                'phone' => '05321234567',
                'email' => 'ahmet.yilmaz@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fatma',
                'surname' => 'Demir',
                'phone' => '05431234567',
                'email' => 'fatma.demir@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mehmet',
                'surname' => 'Kaya',
                'phone' => '05551234568',
                'email' => 'mehmet.kaya@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ayşe',
                'surname' => 'Özkan',
                'phone' => '05331234567',
                'email' => 'ayse.ozkan@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mustafa',
                'surname' => 'Çelik',
                'phone' => '05441234567',
                'email' => 'mustafa.celik@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Zeynep',
                'surname' => 'Arslan',
                'phone' => '05561234567',
                'email' => 'zeynep.arslan@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emre',
                'surname' => 'Şahin',
                'phone' => '05341234567',
                'email' => 'emre.sahin@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Elif',
                'surname' => 'Koç',
                'phone' => '05451234567',
                'email' => 'elif.koc@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Burak',
                'surname' => 'Aydın',
                'phone' => '05571234567',
                'email' => 'burak.aydin@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Selin',
                'surname' => 'Türk',
                'phone' => '05351234567',
                'email' => 'selin.turk@email.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
