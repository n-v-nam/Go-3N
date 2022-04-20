<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuggestTruck extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "suggest_truck";
    protected $primaryKey = "suggest_truck_id";
    protected $fillable = [
        'book_truck_information_id',
        'post_id',
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
