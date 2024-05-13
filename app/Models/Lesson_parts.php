<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson_parts extends Model
{
    use HasFactory;
    protected $fillable = ['content' , 'lesson_id' , 'part'];
    public function lessons()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }
}
