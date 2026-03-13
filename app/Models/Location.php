<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name', 'address'];

    public function occupancies(): HasMany
    {
        return $this->hasMany(Occupancy::class);
    }
}
