<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookTruckInformation extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_PENDING = 0;
    protected $table = 'book_truck_informations';
    protected $primaryKey = 'book_truck_information_id';
    protected $fillable = [
        'customer_id',
        'user_id',
        'from_city_id',
        'to_city_id',
        'item_type_id',
        'category_truck_id',
        'weight_product',
        'price',
        'width',
        'length',
        'height',
        'count',
        'status',
    ];

}
