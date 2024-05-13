<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class controllers extends Model
{
    use HasFactory;

    protected $fillable = [ 'name'	,'status'	,'sensor_id',	'board_id',	'pin'  , 'user_id'];

    
    public function boards(): BelongsTo
    {
        return $this->belongsTo(boards::class, 'board_id');
    }
    public function sensors(): BelongsTo
    {
        return $this->belongsTo(sensors::class, 'sensor_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'foreign_key', 'other_key');
    }
}
