<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\BookTruckInformation;
use App\Http\Controllers\Api\BaseController;
use App\Models\OrderInformations;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardController extends BaseController
{
    public function __construct(DashboardServiceInterface $dashboradService)
    {
        $this->dashboardService = $dashboradService;
    }

    public function dashBoard(Request $request)
    {
        list($status, $data) = $this->dashboardService->dashboard($request->all());
        if (!$status) {
            return $this->withSuccessMessage($data);
        }

        return $this->withData($data, "Dashboard");
    }
}
