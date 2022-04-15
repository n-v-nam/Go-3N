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
}
