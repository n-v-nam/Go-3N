<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\ReportDriver;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Services\Contracts\ReportDriverServiceInterface;

class ReportDriverController extends BaseController
{
    public function __construct(ReportDriverServiceInterface $reportDriverService)
    {
        $this->reportDriver = new ReportDriver();
        $this->customer = new Customer();
        $this->reportDriverService = $reportDriverService;
    }

    public function store(Request $request)
    {
        $currentCustomer = Auth::user();
        $customerId = $driverId = $reportType = null;
        $validateRequest = [
            'title' => 'required',
            'content' => 'required'
        ];
        if ($request["report_type"] == 1) {
            $validateRequest['phone'] = 'required';
            $customer = $this->customer->where("phone", $request["phone"])->first() ?? null;
            if (!$customer) {
                return $this->sendError("Không có người dùng nào ứng vs số điện thoại trên");
            }
            $customerId = !$currentCustomer->customer_type ? $customer->id : $currentCustomer->id;
            $driverId =  !$currentCustomer->customer_type ? $currentCustomer->id : $customer->id;
            $reportType = !$currentCustomer->customer_type ? ReportDriver::DRIVER_REPORT_CUSTOMER : ReportDriver::CUSTOMER_REPORT_DRIVER;
        } else {
            $customerId = !$currentCustomer->customer_type ? null : $currentCustomer->id;
            $driverId = !$currentCustomer->customer_type ? $currentCustomer->id : null;
            $reportType = !$currentCustomer->customer_type ? ReportDriver::DRIVER_REPORT_ADMIN : ReportDriver::CUSTOMER_REPORT_ADMIN;
        }
        $validated = Validator::make($request->all(), $validateRequest);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }

        $driverReport = $this->reportDriver->create([
            "customer_id" => $customerId,
            "driver_id" => $driverId,
            "report_type" => $reportType,
            "title" => $request["title"],
            "content" => $request["content"]
        ]);

        return $this->withData($driverReport, "Tạo report thành công!");
    }

    public function index()
    {
        list($status, $data) = $this->reportDriverService->index();
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Danh sách report");
    }

    public function destroy($id)
    {
        $driverReport = $this->reportDriver->findOrFail($id);
        $driverReport->delete();

        return $this->withSuccessMessage("Đã xóa phản hồi");
    }
}
