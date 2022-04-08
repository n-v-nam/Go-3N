<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_PENDING = 0;
    const STATUS_ENABLE = 1;
    protected $table = 'truck';
    protected $primaryKey = 'truck_id';
    protected $fillable = [
        'license_plates',
        'license_plates_image',
        'customer_id',
        'category_truck_id',
        'name',
        'width',
        'length',
        'height',
        'weight',
        'weight_items',
        'count_order',
        'location_now_city_id',
        'location_now_at',
        'status',
        'user_id_accept',
        'verified_at',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function categoryTruck()
    {
        return $this->hasOne(CategoryTruck::class, 'category_truck_id', 'category_truck_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'city_id', 'location_now_city_id');
    }
}
