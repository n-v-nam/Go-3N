<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonnelNotification extends Model
{
    use HasFactory, SoftDeletes;
    const STATUS_UNREAD = 0;
    const STATUS_READ =1;
    protected $table = "personnel_notifications";
    protected $primaryKey = 'personnel_notification_id';
    protected $fillable = [
        'title',
        'notification_avatar',
        'link',
        'notification_status',
        'user_id',
        'user_read_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
