<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccount extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'user_accounts';

    protected $fillable = [
        'username',
        'password',
        'last_login',
        'role',
        'employee_id',
        'customer_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * UserAccount belongs to an Employee (FK: employee_id on user_accounts)
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * UserAccount belongs to a Customer (FK: customer_id on user_accounts)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
