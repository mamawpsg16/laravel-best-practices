<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected static function booted()
    {
        static::creating(function ($model) {
            // Get the authenticated user ID
            $userId = auth()->id();
    
            // Get the maximum order for the current user
            $maxOrder = static::where('user_id', $userId)
            ->where('status', $model->status)
            ->max('order');
    
            // Increment the maximum order by 1
            $model->order = $maxOrder + 1;
    
            // Set the user_id for the new record
            $model->user_id = $userId;
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
