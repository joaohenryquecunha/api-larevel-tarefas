<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtarefa extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'task_id'];

    public function tarefa()
    {
        return $this->belongsTo(Tarefa::class, 'task_id');
    }
}
