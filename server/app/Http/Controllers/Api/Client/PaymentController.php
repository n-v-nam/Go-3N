<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Services\Contracts\PaymentServiceInterface;

class PaymentController extends BaseController
{
    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->customer = new Customer();
        $this->paymentService = $paymentService;
    }

    public function addMonney($customerId, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'bank_code' => 'required',
            'content' => 'max:255'
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        list($status, $data) = $this->paymentService->addMoney($customerId, $request->all());
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Nạp tiền thành công");
    }

    public function saveBill(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'vnp_Amount' => 'required|numeric',
            'vnp_BankCode' => 'required',
            'vnp_TxnRef' => 'required|max:8'
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }

        list($status, $data) = $this->paymentService->saveBill($request->all());
        if (!$status) {
            return $this->sendError("Đã xảy ra lỗi");
        }

        return $this->withSuccessMessage("Đã lưu hóa đơn");
    }
}
