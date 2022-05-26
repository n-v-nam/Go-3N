<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDriver extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "report_driver";
    protected $primaryKey = "report_driver_id";
    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;
    const CUSTOMER_REPORT_DRIVER = 0;
    const DRIVER_REPORT_CUSTOMER = 1;
    const CUSTOMER_REPORT_ADMIN = 2;
    const DRIVER_REPORT_ADMIN = 3;
    protected $fillable = [
        "customer_id",
        "driver_id",
        "report_type",
        "title",
        "content",
        "status"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo(Customer::class, 'driver_id', 'id');
    }
}
