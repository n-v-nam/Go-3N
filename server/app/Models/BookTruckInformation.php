<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookTruckInformation extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_PENDING = 0;
    const STATUS_DRIVER_SUGGEST_ACCEPT = 1;
    const STATUS_BOTH_ACCEPT = 2;
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function fromCity()
    {
        return $this->belongsTo(City::class, 'from_city_id', 'city_id');
    }

    public function toCity()
    {
        return $this->belongsTo(City::class, 'to_city_id', 'city_id');
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id', 'item_type_id');
    }

    public function suggestTruck()
    {
        return $this->hasMany(SuggestTruck::class, 'book_truck_information_id', 'book_truck_information_id');
    }

    public function categoryTruck()
    {
        return $this->belongsTo(CategoryTruck::class, 'category_truck_id', 'category_truck_id');
    }

}
