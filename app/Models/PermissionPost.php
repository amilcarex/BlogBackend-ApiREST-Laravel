<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionPost extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
