<?php

namespace App\Services;
use App\Services\Contracts\BookTruckInformationServiceInterface;
use App\Models\BookTruckInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\OrderInformations;
use App\Models\CustomerNotification;
use App\Models\City;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class BookTruckInformationService implements BookTruckInformationServiceInterface
{
    public function __construct()
    {
        $this->bookTruckInformation = new BookTruckInformation();
        $this->customerNotification = new CustomerNotification();
        $this->orderInformation = new OrderInformations();
        $this->post =  new Post();
    }

    public function bookTruck($postId, $params)
    {
        $bookTruckInformationLastest = $this->bookTruckInformation->where('customer_id', Auth::user()->id)->first() ?? null;
        $params = array_slice($params, 0, 3);
        $driverIdSuggestTrucks = array();
        //dd($this->post->findOrFail($postId)->truck->customer->id);
        foreach ($params as $k => $param) {
            if ($this->post->findOrFail($param)->truck->customer->id
                !== $this->post->findOrFail($postId)->truck->customer->id) {
                    $driverIdSuggestTrucks[$k] = $param;
            }
        }

        if (!$bookTruckInformationLastest) {
            return [false, "Bạn chưa nhập thông tin của hàng hóa"];
        }
        DB::beginTransaction();
        try {
            $orderInformation = $this->orderInformation->create([
                'code_order' => "#CUS" . Auth::user()->id . "P" . $postId . Str::random(5),
                'book_truck_information_id' => $bookTruckInformationLastest->book_truck_information_id,
                'post_id' => $postId,
                'status' => OrderInformations::STATUS_WATTING_DRIVER_RECIEVE,
            ]);
            //insert customer notification
            $title = $this->getTitle($bookTruckInformationLastest);
            $customerAvatar = Auth::user()->avatar;
            $customerNotification = $this->customerNotification->create([
                'title' => $title,
                'notification_avatar' => $customerAvatar,
                'link' => "",
                'customer_id' => $this->post->findOrFail($postId)->truck->customer->id,
            ]);
            //send message to driver sms
            // $token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio_sid = getenv("TWILIO_SID");
            // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            // $twilio = new Client($twilio_sid, $token);
            // try {
            //     $message = $twilio->messages
            //         ->create($this->post->findOrFail($postId)->truck->customer->phone, // to
            //                [
            //                    "body" => Auth::user()->name . Auth::user()->phone . " Đã đặt xe của bạn từ " . City::findOrFail($bookTruckInformationLastest->from_city_id)->name . " đến " . City::findOrFail($bookTruckInformationLastest->to_city_id)->name .
            //                                 " ,Hãy truy cập http://localhost:8080/client/notification để xác nhận hoặc từ chối",
            //                    "from" => "+18144984469"
            //                ]
            //         );

            // } catch (\Exception $e) {
            //     return [false, "Số điện thoại không hợp lệ!"];
            // }
            //update status post
            $this->post->findOrFail($postId)->update([
                'status' => OrderInformations::STATUS_WATTING_DRIVER_RECIEVE
            ]);
            //update status order information after 10 phut
            $statusWatingDriverRecieve = OrderInformations::STATUS_WATTING_DRIVER_RECIEVE;
            $statusDriverRefuse = OrderInformations::STATUS_DRIVER_REFUSE;
            $q =  "CREATE EVENT IF NOT EXISTS update_status_event_$orderInformation->order_information_id
                ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 MINUTE
                DO
                UPDATE order_informations SET status = $statusDriverRefuse WHERE order_information_id = $orderInformation->order_information_id and status = $statusWatingDriverRecieve;
                UPDATE post SET status = $statusDriverRefuse WHERE post_id = $postId and STATUS = $statusWatingDriverRecieve;";
            if (count($driverIdSuggestTrucks) > 0) {
                foreach($driverIdSuggestTrucks as $key => $driverIdSuggestTruck) {
                    $customer_id = $this->post->findOrFail($driverIdSuggestTruck)->truck->customer->id;
                    $q = $q . "INSERT INTO suggest_truck(book_truck_information_id,post_id) VALUES($bookTruckInformationLastest->book_truck_information_id, $driverIdSuggestTruck);";
                    $q = $q . "INSERT INTO customer_notification(title,notification_avatar,link,customer_id) VALUES('$title', '$customerAvatar', '', $customer_id);";
                }
            }

            DB::unprepared($q);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Bạn đã đặt xe và chờ tài xế phản hồi"];
    }

    public function getTitle($bookTruckInformationLastest)
    {
        return "Khách hàng " . Auth::user()->name . Auth::user()->phone . " Đã đặt xe của bạn từ " . City::findOrFail($bookTruckInformationLastest->from_city_id)->name . " đến " . City::findOrFail($bookTruckInformationLastest->to_city_id)->name;
    }
}
