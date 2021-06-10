<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['body', 'user_id'];
    protected $appends = ['file'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'related_id')->where('type', 'post');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getFileAttribute()
    {
       return $this->files()->first()  ? URL::asset(Storage::url('/images/posts/'.$this->files()->first()->file))  : null ;
    }

}
