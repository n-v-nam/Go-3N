<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $table = 'item_type';
    protected $primaryKey = 'item_type_id';
    protected $fillable = [
        'name',
        'slug',
    ];
}
