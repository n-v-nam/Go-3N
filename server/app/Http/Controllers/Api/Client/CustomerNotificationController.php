<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerNotification;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerNotificationController extends BaseController
{
    public function __construct()
    {
        $this->customerNotification = new CustomerNotification();
    }

    public function index()
    {
        $customerNotification = $this->customerNotification->where("customer_id", Auth::user()->id)->get();

        return $this->withData($customerNotification, "Danh sách thông báo");
    }

    public function show($id)
    {
        $customerNotification = $this->customerNotification->findOrFail($id);

        return $this->withData($customerNotification, "Chi tiết thông báo");
    }

    public function destroy($id)
    {
        $customerNotification = $this->customerNotification->findOrFail($id);
        $customerNotification->delete();

        return $this->withSuccessMessage("Đã xóa thông báo");
    }

    public function readCustomerNotification($id)
    {
        $customerNotification = $this->customerNotification->findOrFail($id);
        $customerNotification->update([
            "status" => CustomerNotification::STATUS_READ
        ]);

        return $this->withSuccessMessage("Đã đọc thông báo");
    }
}
