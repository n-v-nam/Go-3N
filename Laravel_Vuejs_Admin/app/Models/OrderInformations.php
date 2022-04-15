<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInformations extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_WATTING_DRIVER_RECIEVE = 0;
    const STATUS_DRIVER_ACCEPT = 1;
    const STATUS_DRIVER_REFUSE = 2;
    const STATUS_CUSTOMER_CANCEL = 3;
    protected $table = "order_informations";
    protected $primaryKey = "order_information_id";
    protected $fillable = [
        'code_order',
        'book_truck_information_id',
        'post_id',
        'recieve_at',
        'completed_at',
        'status',
    ];
}
