<?php

namespace App\Services;
use App\Services\Contracts\PaymentServiceInterface;
use App\Models\Customer;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentService extends BaseService implements PaymentServiceInterface
{
    public function __construct()
    {
        $this->customer = new Customer();
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

}
