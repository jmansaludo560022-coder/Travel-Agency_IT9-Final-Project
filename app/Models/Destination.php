<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'city_name',
        'country',
        'description',
        'image',
    ];

    /**
     * Get the travel packages for this destination.
     */
    public function travelPackages(): HasMany
    {
        return $this->hasMany(TravelPackage::class);
    }
}
