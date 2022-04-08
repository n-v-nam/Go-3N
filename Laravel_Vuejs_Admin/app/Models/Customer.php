<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const MALE = 1;
    const HUMAN = 0;
    const FEMAKE = 0;
    const DRIVER = 1;
    const CUSTOMER_BOOK_TRUCK = 1;
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $guarded = 'web';
    protected $fillable = [
        'name',
        'phone',
        'password',
        'sex',
        'customer_type',
        'avatar',
        'is_verified',
        'phone_verified_at',
        'review',
        'count_review',
    ];

    public function driver()
    {
        return $this->where('customer_type', Customer::DRIVER)->get();
    }

    public function personBookTruck()
    {
        return $this->where('customer_type', Customer::CUSTOMER_BOOK_TRUCK)->get();
    }

}
