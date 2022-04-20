<?php

namespace App\Services;
use Twilio\Rest\Client;
use App\Services\Contracts\BaseServiceInterface;
use App\Models\BookTruckInformation;
use App\Models\OrderInformations;
use Illuminate\Support\Str;

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

}
