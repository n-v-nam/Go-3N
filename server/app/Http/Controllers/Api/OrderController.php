<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookTruckInformation;
use App\Models\OrderInformations;
use App\Http\Controllers\Api\BaseController;
use App\Services\Contracts\OrderServiceInterface;

class OrderController extends BaseController
{
    public function __construct(OrderServiceInterface $orderService)
    {
        $this->bookTruckInformation = new BookTruckInformation();
        $this->orderInformation = new OrderInformations();
        $this->orderService = $orderService;
    }

    public function index()
    {
        list($status, $data) = $this->orderService->index();
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "List Order");
    }

    public function show($id)
    {
        list($status, $data) = $this->orderService->show($id);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Chi tiết đơn hàng");
    }

    public function update($id, Request $request)
    {
        list($status, $data) = $this->orderService->update($id, $request->all());
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function destroy($id)
    {
        $orderInformation = $this->orderInformation->findOrFail($id);
        $orderInformation->delete();

        return $this->withSuccessMessage("Đã xóa đơn hàng thành công !");
    }
}
