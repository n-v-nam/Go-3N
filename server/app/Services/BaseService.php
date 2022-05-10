<?php

namespace App\Services;
use Twilio\Rest\Client;
use App\Services\Contracts\BaseServiceInterface;
use App\Models\BookTruckInformation;
use App\Models\OrderInformations;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BaseService implements BaseServiceInterface
{
    public function driverSuggestTrucks($postId, $params)
    {
        if (!$params) {
            return null;
        }
        $driverIdSuggestTrucks = array();
        foreach ($params as $k => $param) {
            if ($this->post->findOrFail($param)->truck->customer->id
                !== $this->post->findOrFail($postId)->truck->customer->id) {
                    $driverIdSuggestTrucks[$k] = $param;
            }
        }
        $driverIdSuggestTrucks = array_slice($driverIdSuggestTrucks, 0, 3);
        // loại các bài post cùng 1 truck
        if (count($driverIdSuggestTrucks) > 1) {
            $countMang = count($driverIdSuggestTrucks);
            for ($i = 0 ; $i < $countMang; $i++){
                for ($j = $i +1 ; $j < $countMang; $j++){
                    if ($this->post->findOrFail($driverIdSuggestTrucks[$i])->truck->customer->id
                        == $this->post->findOrFail($driverIdSuggestTrucks[$i + 1])->truck->customer->id) {
                        array_splice($driverIdSuggestTrucks, $j, 1);
                        $countMang = $countMang -1; // Xóa mất 1 phần tử thì mảng sẽ giảm 1
                        $j = $j-1; // Xóa xong thì lùi lại 1 bước dể tránh bỏ sót 1 phần tử cạnh phần tử vừa xóa
                    }
                }
            }
        }

        return $driverIdSuggestTrucks;
    }

    public function sendSMS($link, $title, $phone)
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        try {
            $message = $twilio->messages
                ->create($phone, // to
                           [
                               "body" => $title,
                               "from" => "+18144984469"
                           ]
                );

        } catch (\Exception $e) {
            return [false, "Số điện thoại không hợp lệ!"];
        }
    }

    public function createOrder($customer, $postId, $bookTruckInformationId, $status)
    {
        $orderInformation = OrderInformations::create([
            'code_order' =>  "#CUS" . $customer->id . "P" . $postId . Str::random(3),
            'book_truck_information_id' => $bookTruckInformationId,
            'post_id' => $postId,
            'status' => $status,
        ]);

        return $orderInformation;
    }

    public function payment($params)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8080/page/loading-money";
        $vnp_TmnCode = "3M1Y5T6X";//Mã website tại VNPAY
        $vnp_HashSecret = "EMVTHEBZYBILBBKAUCGZDTMOGVBQOHLI"; //Chuỗi bí mật

        $vnp_TxnRef = $params['bill_code']; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Nạp tiền";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $params['amount'];
        $vnp_Locale = "vn";
        $vnp_BankCode = $params['bank_code'];
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        $vnp_ExpireDate = Carbon::now()->addMinutes(30);
        //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address=$_POST['txt_inv_addr1'];
        // $vnp_Bill_City=$_POST['txt_bill_city'];
        // $vnp_Bill_Country=$_POST['txt_bill_country'];
        // $vnp_Bill_State=$_POST['txt_bill_state'];
        // // Invoice
        // $vnp_Inv_Phone=$_POST['txt_inv_mobile'];
        // $vnp_Inv_Email=$_POST['txt_inv_email'];
        // $vnp_Inv_Customer=$_POST['txt_inv_customer'];
        // $vnp_Inv_Address=$_POST['txt_inv_addr1'];
        // $vnp_Inv_Company=$_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode=$_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type=$_POST['cbo_inv_type'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=> $vnp_ExpireDate,
            // "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
            // "vnp_Bill_Email"=>$vnp_Bill_Email,
            // "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
            // "vnp_Bill_LastName"=>$vnp_Bill_LastName,
            // "vnp_Bill_Address"=>$vnp_Bill_Address,
            // "vnp_Bill_City"=>$vnp_Bill_City,
            // "vnp_Bill_Country"=>$vnp_Bill_Country,
            // "vnp_Inv_Phone"=>$vnp_Inv_Phone,
            // "vnp_Inv_Email"=>$vnp_Inv_Email,
            // "vnp_Inv_Customer"=>$vnp_Inv_Customer,
            // "vnp_Inv_Address"=>$vnp_Inv_Address,
            // "vnp_Inv_Company"=>$vnp_Inv_Company,
            // "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
            // "vnp_Inv_Type"=>$vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        Log::info($vnp_Url);
        return $vnp_Url;

        // $returnData = array('code' => '00'
        //     , 'message' => 'success'
        //     , 'data' => $vnp_Url);
        //     // if (isset($_POST['redirect'])) {
        //         header('Location: ' . );
        //         die();
            // } else {
            //     echo json_encode($returnData);
            // }
        // vui lòng tham khảo thêm tại code demo
    }

}
