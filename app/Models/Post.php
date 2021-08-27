<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\PermissionPost;
use App\Models\Comment;
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'tittle', 'content', 'slug', 'image', 'visibility', 'author_name', 'author_id'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    public function permissionpost()
    {
        return $this->belongsToMany(PermissionPost::class)->withTimestamps();
    }
    public function comments()
    {
        return $this->belongsToMany(Comment::class)->withTimestamps();
    }
}
