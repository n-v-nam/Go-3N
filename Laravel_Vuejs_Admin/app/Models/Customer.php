<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const MALE = 1;
    const HUMAN = 0;
    const FEMAKE = 0;
    const DRIVER = 1;
    const CUSTOMER_BOOK_TRUCK = 1;
    protected $table = 'customers';
    protected $primaryKey = 'id';
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

}
