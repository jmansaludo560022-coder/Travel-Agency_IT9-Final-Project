<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TravelPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'destination_id',
        'package_name',
        'package_type',
        'description',
        'itinerary',
        'inclusions',
        'exclusions',
        'package_cost',
        'start_date',
        'end_date',
        'slots_available',
        'image',
        'is_visible',
        'approval_status',
        'pending_changes',
    ];

    protected function casts(): array
    {
        return [
            'start_date'      => 'date',
            'end_date'        => 'date',
            'package_cost'    => 'decimal:2',
            'is_visible'      => 'boolean',
            'pending_changes' => 'array',
        ];
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Booking::class, 'package_id', 'booking_id');
    }
}
