<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Kullanıcının cüzdanı
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Kullanıcının gönderdiği arkadaşlık istekleri
     */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }

    /**
     * Kullanıcının aldığı arkadaşlık istekleri
     */
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }

    /**
     * Kullanıcının arkadaşları (kabul edilmiş)
     */
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    /**
     * Kullanıcının arkadaşları (karşılıklı)
     */
    public function mutualFriends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps()
                    ->union(
                        $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                            ->wherePivot('status', 'accepted')
                            ->withTimestamps()
                    );
    }
}
