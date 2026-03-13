<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occupancy extends Model
{
    protected $fillable = [
        'job_title',
        'department_id',
        'location_id',
        'work_mode',
        'job_description',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status'    => 'string',
        'work_mode' => 'string',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
