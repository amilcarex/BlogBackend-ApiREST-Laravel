<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'category_id', 'slug', 'description', 'default'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function childrenCategories()
    {
        return $this->hasMany(Category::class)->with('categories');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
