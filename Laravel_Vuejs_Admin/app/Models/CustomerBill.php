<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBill extends Model
{
    use HasFactory;
    protected $table = "customer_bill";
    protected $primaryKey = "customer_bill_id";
    protected $fillable = [
        'customer_id',
        'customer_bill_code',
        'amount',
        'bank_code',
        'content'
    ];
}
