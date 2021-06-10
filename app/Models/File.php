<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $guarded = [];
    use HasFactory;

    public function filable()
    {
        return $this->morphTo();
    }
}
