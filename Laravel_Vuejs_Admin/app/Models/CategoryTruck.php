<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTruck extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $table = 'category_truck';
    protected $primaryKey = 'category_truck_id';
    protected $fillable = [
        'lisense_plates',
        'name',
        'slug',
    ];
}
