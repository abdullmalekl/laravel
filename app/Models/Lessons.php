<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Lesson_parts;
class Lessons extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description' , 'image' , 'user_id'];
    public function lesson_parts(): HasMany
    {
        return $this->hasMany(Lesson_parts::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
