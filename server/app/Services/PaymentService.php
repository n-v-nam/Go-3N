<?php

namespace App\Services;
use App\Services\Contracts\PaymentServiceInterface;
use App\Models\Customer;
use App\Models\CustomerBill;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaymentService extends BaseService implements PaymentServiceInterface
{
    public function __construct()
    {
        $this->customer = new Customer();
        $this->customerBill = new CustomerBill();
    }

    public function addMoney(array $params)
    {
        $customer = Auth::user();
        $params['bill_code'] = "#" . STR::random(5);
        $payment = $this->payment($params);
        if (!empty($payment)) {
            return [true, $payment];
        }

        return [false, "Nạp tiền thất bại"];
    }

    public function saveBill(array $params)
    {
        DB::beginTransaction();
        try {
            $customerBill = $this->customerBill->create([
                "customer_id" => Auth::user()->id,
                "customer_bill_code" => $params["vnp_TxnRef"],
                "amount" => $params["vnp_Amount"],
                "bank_code" => $params["vnp_BankCode"],
            ]);
            $customer = Auth::user();
            $oldBalance = $customer->balance;
            $customer->update([
                "balance" => $oldBalance + $params["vnp_Amount"],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }


        return [true, "Đã lưu hóa đơn"];
    }

}
