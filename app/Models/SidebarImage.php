<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class SidebarImage extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'image', 'active'
    ];
    protected $hidden = [
        
    ];


};
