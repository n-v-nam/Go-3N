<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComment extends Model
{
    use HasFactory;
    protected $table = "customer_comment";
    protected $primaryKey = "customer_comment_id";
    protected $fillable = [
        "customer_id",
        "driver_id",
        "content",
        "rate"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }
}
