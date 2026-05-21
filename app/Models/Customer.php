<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'cus_fn',
        'cus_mn',
        'cus_ln',
        'cus_email',
        'phone_num',
    ];

    /**
     * Customer has one UserAccount (FK customer_id is on user_accounts)
     */
    public function userAccount(): HasOne
    {
        return $this->hasOne(UserAccount::class, 'customer_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
