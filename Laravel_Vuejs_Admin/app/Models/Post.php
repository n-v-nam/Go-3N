<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_HET_HAN = 0;
    const STATUS_HIEN_THI_CHUA_NHAN_HANG = 1;
    const STATUS_HIEN_THI_DA_NHAN_CHUYEN = 2;
    protected $table = 'post';
    protected $primaryKey = 'post_id';
    protected $fillable = [
        'truck_id',
        'title',
        'content',
        'from_city_id',
        'to_city_id',
        'post_type',
        'weight_product',
        'lowest_price',
        'highest_price',
        'start_date',
        'end_date',
        'is_approve',
        'is_approve_at',
        'user_id',
        'status',
    ];

    public function fromCity()
    {
        return $this->hasOne(City::class, 'city_id', 'from_city_id');
    }

    public function toCity()
    {
        return $this->hasOne(City::class, 'city_id', 'to_city_id');
    }

    public function image()
    {
        return $this->hasMany(PostImage::class, 'post_id', 'post_id');
    }

    public function postItemType()
    {
        return $this->hasMany(PostItemType::class, 'post_id', 'post_id');
    }

    public function itemType()
    {
        return $this->belongsToMany(ItemType::class, 'post_item_type', 'post_id', 'item_type_id');
    }

    public function truck()
    {
        return $this->hasOne(Truck::class, 'truck_id', 'truck_id');
    }
}
