<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'emp_fn',
        'emp_mn',
        'emp_ln',
        'emp_gender',
        'emp_birthdate',
        'emp_address',
        'emp_contact_num',
        'emp_email',
        'emp_hiredate',
        'commission_rate',
    ];

    protected function casts(): array
    {
        return [
            'emp_birthdate' => 'date',
            'emp_hiredate' => 'date',
            'commission_rate' => 'decimal:4',
        ];
    }

    /**
     * Employee has one UserAccount (FK employee_id is on user_accounts)
     */
    public function userAccount(): HasOne
    {
        return $this->hasOne(UserAccount::class, 'employee_id');
    }

    public function travelPackages(): HasMany
    {
        return $this->hasMany(TravelPackage::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }
}
