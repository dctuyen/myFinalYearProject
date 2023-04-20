<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
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
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'sex',
        'birthday',
        'address',
        'background_url',
        'role_id',
        'creator',
        'updater',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function getToken(): string
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

//    public function createToken($user): void
//    {
//        if ($user->role_id === Constants::STUDENT_ROLE_ID) {
//            $user->remember_token = $this->getToken();
//            $user->save();
//        }
//    }
//
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::saving(static function ($user) {
//            $user->createToken($user);
//        });
//    }
}
