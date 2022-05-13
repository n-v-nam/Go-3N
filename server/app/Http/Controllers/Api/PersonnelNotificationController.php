<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonnelNotification;
use App\Http\Controllers\Api\BaseController;

class PersonnelNotificationController extends BaseController
{
    public function __construct()
    {
        $this->personnelNotification = new PersonnelNotification();
    }

    public function index()
    {
        $notifications = $this->personnelNotification->leftJoin('personnel', 'personnel_notifications.user_id', 'personnel.id')
            ->select('personnel_notification_id', 'title', 'notification_avatar', 'link', 'notification_status', 'personnel.email', 'user_read_at')->get();

        return $this->withData($notifications, "Danh sách thông báo");
    }

    public function destroy($id)
    {
        $notification = $this->personnelNotification->findOrFail($id);
        $notification->delete();

        return $this->withSuccessMessage("Đã xóa thông báo này");
    }

    public function readPersonnelNotification($id)
    {
        $notification = $this->personnelNotification->findOrFail($id);
        $notification->update([
            "status" => PersonnelNotification::STATUS_READ
        ]);

        return $this->withSuccessMessage("Đã đọc thông báo");
    }
}
