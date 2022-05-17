<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDriver extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "report_driver";
    protected $primaryKey = "report_id";
    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;
    protected $fillable = [
        "customer_id",
        "target_id",
        "title",
        "content",
        "status"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function target()
    {
        return $this->belongsTo(Customer::class, 'target_id', 'id');
    }
}
