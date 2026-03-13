<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'occupancy_id',
        'full_name',
        'email',
        'phone_number',
        'message',
        'file_path',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function occupancy(): BelongsTo
    {
        return $this->belongsTo(Occupancy::class);
    }
}
