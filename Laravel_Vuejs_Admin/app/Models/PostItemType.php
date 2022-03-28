<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostItemType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'post_item_type';
    protected $primaryKey = 'post_item_type_id';
    protected $fillable = [
        'post_id',
        'item_type_id',
    ];
}
