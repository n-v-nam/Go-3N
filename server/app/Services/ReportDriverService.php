<?php

namespace App\Services;
use App\Services\Contracts\ReportDriverServiceInterface;
use App\Models\ReportDriver;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class ReportDriverService extends BaseService implements ReportDriverServiceInterface
{
    public function __construct()
    {
        $this->customer = new Customer();
        $this->reportDriver = new ReportDriver();
    }

    public function index()
    {
        if (Auth::user()->getGuarded() == "admin") {
            $driverReports = $this->reportDriver->all() ?? null;
        }
        if (Auth::user()->getGuarded() == "web" && Auth::user()->customer_type == Customer::CUSTOMER_BOOK_TRUCK) {
            $driverReports = $this->reportDriver->where("customer_id", Auth::user()->id)->get() ?? null;
        }
        if (Auth::user()->getGuarded() == "web" && Auth::user()->customer_type == Customer::DRIVER) {
            $driverReports = $this->reportDriver->where("driver_id", Auth::user()->id)->get() ?? null;
        }
        $listReport = array();
        foreach ($driverReports as $k => $driverReport) {
            $listReport[$k]["title"] = $driverReport->title;
            $listReport[$k]["content"] = $driverReport->content;
            $listReport[$k]["customer_name"] = $driverReport->customer->name;
            $listReport[$k]["customer_phone"] = $driverReport->customer->phone;
            $listReport[$k]["report_type"] = $driverReport->report_type;
            $listReport[$k]["driver_name"] = $driverReport->driver->name;
            $listReport[$k]["driver_phone"] = $driverReport->driver->phone;
            $listReport[$k]["status"] = $driverReport->status;
        }

        return [true,
               !empty($listReport) ? array_values($listReport) : []
            ];
    }
}
