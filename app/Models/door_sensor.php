<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class door_sensor extends Model
{
    use HasFactory;

    protected $fillable = [ 'code' , 'identify'	,'sensor_id', 'board_id'];

    
    public function boards(): BelongsTo
    {
        return $this->belongsTo(boards::class, 'board_id');
    }
    public function sensors(): BelongsTo
    {
        return $this->belongsTo(sensors::class, 'sensor_id');
    }
}
