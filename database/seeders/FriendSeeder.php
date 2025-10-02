<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        // Test kullanıcısı ile diğer kullanıcılar arasında arkadaşlık ilişkileri oluştur
        $testUser = $users->first(); // Test kullanıcısı
        
        foreach ($users->skip(1)->take(5) as $friend) {
            // Test kullanıcısından diğer kullanıcılara arkadaşlık isteği gönder
            Friend::firstOrCreate(
                ['user_id' => $testUser->id, 'friend_id' => $friend->id],
                ['status' => 'accepted']
            );
            
            // Karşılıklı arkadaşlık ilişkisi oluştur
            Friend::firstOrCreate(
                ['user_id' => $friend->id, 'friend_id' => $testUser->id],
                ['status' => 'accepted']
            );
        }
        
        // Diğer kullanıcılar arasında da bazı arkadaşlık ilişkileri oluştur
        $otherUsers = $users->skip(1)->take(3);
        
        foreach ($otherUsers as $user) {
            foreach ($otherUsers->where('id', '!=', $user->id)->take(2) as $friend) {
                Friend::firstOrCreate(
                    ['user_id' => $user->id, 'friend_id' => $friend->id],
                    ['status' => 'accepted']
                );
                
                Friend::firstOrCreate(
                    ['user_id' => $friend->id, 'friend_id' => $user->id],
                    ['status' => 'accepted']
                );
            }
        }
    }
}
