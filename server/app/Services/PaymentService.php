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

    public function addMoney($customerId, array $params)
    {
        $customer = $this->customer->findOrFail($customerId);
        $params['bill_code'] = "#" . STR::random(5);
        $payment = $this->payment($params);
        if (!empty($payment)) {
            dd($payment);
            // header('Location: ' . $payment);
            die();
        }

        return [false, "Nạp tiền thất bại"];
    }

    public function saveBill(array $params)
    {
        DB::beginTransaction();
        try {
            $customerBill = $this->customerBill->craete([
                "customer_id" => Auth::user()->id,
                "customer_bill_code" => $params["vnp_TxnRef"],
                "amount" => $params["vnp_Amount"],
                "bank_code" => $params["vnp_BankCode"],
            ]);
            $customer = Auth::user();
            $oldBalance = $customer->balance;
            $customer->update([
                "amount" => $oldBalance + $params["vnp_Amount"],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
            DB::rollBack();
        }


        return [true, "Đã lưu hóa đơn"];
    }

}
