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
        $validated = Validator::make($request->all(), [
            'phone' => 'string',
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $driver = $this->customer->where("phone", $request["phone"])->where("customer_type", Customer::DRIVER)->first() ?? null;
        if (!$driver) {
            return $this->sendError("Không có tài xế nào ứng vs số điện thoại trên");
        }

        $driverReport = $this->reportDriver->create([
            "customer_id" => Auth::user()->id,
            "driver_id" => $request["phone"] ? $driver->id : null,
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
