<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project_parts;

class Platform_prjects extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description' , 'image' , 'user_id'];
    public function lesson_parts()
    {
        return $this->hasMany(Project_parts::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
