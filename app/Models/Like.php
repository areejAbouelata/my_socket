<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $table = 'likes';
    protected $guarded = [];
    protected $fillable = ['is_liked', 'user_id', 'related_id', 'type'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'related_id');
    }
}
