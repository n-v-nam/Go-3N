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
    const STATUS_CUSTOMER_CANCEL_AFTER_DRIVER_ACCEPT = 4;
    const STATUS_DRIVER_CANCEL_AFTER_BOTH_ACCPET = 5;
    const STATUS_BOTH_ACCEPT = 6;
    const STATUS_ORDER_FAIL = 7;
    const STATUS_CUSTOMER_PAID = 8;
    const STATUS_COMPLETED = 9;
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

    public function bookTruckInformation()
    {
        return $this->belongsTo(BookTruckInformation::class, 'book_truck_information_id', 'book_truck_information_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
