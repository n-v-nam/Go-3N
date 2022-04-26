<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistanceCityVN extends Model
{
    use HasFactory;

    protected $table = 'distance_city_vn';
    protected $fillable = [
        'from_city_id',
        'to_city_id',
        'from_city',
        'to_city',
        'distance',
    ];
}
