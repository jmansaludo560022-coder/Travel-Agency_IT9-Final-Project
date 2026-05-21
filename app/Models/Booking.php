<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'package_id',
        'employee_id',
        'booking_date',
        'travel_date',
        'total_amount',
        'booking_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'travel_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the customer that owns the booking.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the travel package for this booking.
     */
    public function travelPackage(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class, 'package_id');
    }

    /**
     * Get the employee handling this booking.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the travelers for this booking.
     */
    public function travelers(): HasMany
    {
        return $this->hasMany(Traveler::class);
    }

    /**
     * Get the payments for this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the review for this booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
