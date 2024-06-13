<?php

namespace App\Models;

use App\Models\Task;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword 
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

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
        'banned_at' => 'datetime:Y-m-d H:i:s',
        'banned_end_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeIsVerified($q){
        return $q->whereNotNull('email_verified_at');
    }
    public function isAdmin(){
        return $this->is_admin;
    }

    public function isBanned()
    {
        return $this->banned_at;
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }
}
