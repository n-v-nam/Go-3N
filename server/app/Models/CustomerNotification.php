<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerNotification extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_UNREAD = 0;
    const STATUS_READ =1;
    protected $table = "customer_notification";
    protected $primaryKey = "customer_notification_id";
    protected $fillable = [
        'title',
        'notification_avatar',
        'link',
        'notification_status',
        'customer_id',
        'customer_read_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id', 'customer_id');
    }
}
