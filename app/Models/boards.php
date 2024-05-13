<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class boards extends Model
{
    use HasFactory;

    protected $fillable = ['deviceName'	,'Auth_code',	'project_id',	'user_id'];

 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
   
    public function project(): BelongsTo
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }
    
    public function controllers(): HasMany
    {
        return $this->hasMany(controllers::class, 'board_id' );
    }
    public function readers(): HasMany
    {
        return $this->hasMany(readers::class, 'board_id' );
    }
    public function door_sensor(): HasMany
    {
        return $this->hasMany(door_sensor::class, 'board_id' );
    }
}
