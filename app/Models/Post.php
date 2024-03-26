<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    protected $attributes = [
        'author_id' => 5,
    ];
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

}
