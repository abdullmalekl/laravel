<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class sensors extends Model
{
    use HasFactory;

    protected $fillable = [ 'sensorName',	'classified' ];

    
    public function controllers(): HasMany
    {
        return $this->hasMany(controllers::class, 'sensor_id');
    }
    public function readers(): HasMany
    {
        return $this->hasMany(readers::class, 'sensor_id');
    }
    public function door_sensor(): HasMany
    {
        return $this->hasMany(door_sensor::class, 'sensor_id');
    }
}
