<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'date_to_end', 'end_at', 'task_id', 'status_id'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function childrenTasks()
    {
        return $this->hasMany(Task::class)->with('tasks');
    }
}
