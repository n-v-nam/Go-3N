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
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\sendSMSDriverUnresponsive1;
use Carbon\Carbon;
use App\Models\Customer;
use App\Notifications\SuggestTruckForDriver;
use Illuminate\Support\Facades\Cookie;
use App\Services\BaseService;

class BookTruckInformationService extends BaseService implements BookTruckInformationServiceInterface
{
    public function __construct()
    {
        $this->bookTruckInformation = new BookTruckInformation();
        $this->customerNotification = new CustomerNotification();
        $this->orderInformation = new OrderInformations();
        $this->post =  new Post();
    }

    public function bookTruck($postId)
    {
        $bookTruckInformationLastest = $this->bookTruckInformation->where('customer_id', Auth::user()->id)
            ->orderBy('book_truck_information_id', 'desc')->first() ?? null;
        $customer = Auth::user();
        $driver = $this->post->findOrFail($postId)->truck->customer;
        if (!$bookTruckInformationLastest) {
            return [false, "Bạn chưa nhập thông tin của hàng hóa"];
        }
        $params = !unserialize(Cookie::get("book_truck_information_id" . $bookTruckInformationLastest->book_truck_information_id)) ? null : unserialize(Cookie::get("book_truck_information_id" . $bookTruckInformationLastest->book_truck_information_id));
        $driverIdSuggestTrucks = !empty($this->driverSuggestTrucks($postId, $params)) ? $this->driverSuggestTrucks($postId, $params) : null;
        DB::beginTransaction();
        try {
            $orderInformation = $this->createOrder($customer, $postId, $bookTruckInformationLastest->book_truck_information_id, OrderInformations::STATUS_WATTING_DRIVER_RECIEVE);
            //insert customer notification
            $link = "http://localhost:8080/driver/?order-information=" . $orderInformation->order_information_id;
            $title = $customer->name . " sđt " . $customer->phone . " Đã đặt xe của bạn từ " . City::findOrFail($bookTruckInformationLastest->from_city_id)->name . " đến " . City::findOrFail($bookTruckInformationLastest->to_city_id)->name
                        . " Hãy truy cập vào " . $link . " để xác nhận hoặc từ chối";
            $customerNotification = $this->customerNotification->create([
                'title' => $title,
                'notification_avatar' => Auth::user()->avatar,
                'link' => "",
                'customer_id' => $this->post->findOrFail($postId)->truck->customer->id,
            ]);
            //send message to driver sms
            //$this->sendSMS($link, $title, $driver->phone);
            //update status post
            $this->post->findOrFail($postId)->update([
                'status' => Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN,
            ]);
            //send mail to driver if email_verified_at is not null
            if (!empty($driver->email_verified_at)) {
                $driver->notify(new SuggestTruckForDriver($link, $title));
            }
            //update status order information after 10 phut
            $statusWatingDriverRecieve = OrderInformations::STATUS_WATTING_DRIVER_RECIEVE;
            $statusDriverRefuse = OrderInformations::STATUS_DRIVER_REFUSE;
            $statusHienThi = Post::STATUS_HIEN_THI_CHUA_NHAN_HANG;
            $q =  "CREATE EVENT IF NOT EXISTS update_status_event_$orderInformation->order_information_id
                ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 MINUTE
                DO
                UPDATE order_informations SET status = $statusDriverRefuse WHERE order_information_id = $orderInformation->order_information_id and status = $statusWatingDriverRecieve;
                UPDATE post SET status = $statusHienThi WHERE post_id = $postId and STATUS = $statusWatingDriverRecieve;";

            DB::unprepared($q);
            //check post status after 20 phut demo để 2 phút
            if (!empty($driverIdSuggestTrucks)) {
                $customer = Customer::findOrFail(Auth::user()->id);
                dispatch(new sendSMSDriverUnresponsive1($orderInformation, $driverIdSuggestTrucks, $customer))->delay(Carbon::now()->addMinutes(2));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Bạn đã đặt xe và chờ tài xế phản hồi"];
    }

    public function customerCancelOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        $post = $this->post->findOrFail($orderInformation->post_id);
        $post->update([
            'status' => Post::STATUS_HIEN_THI_CHUA_NHAN_HANG,
        ]);
        $customer = Auth::user();
        $driver = $post->truck->customer;
        $newStatus = $message = "";
        $link = "http://localhost:8080/client-customer/payment/?order-information=" . $orderInformation->order_information_id;
        if ($orderInformation->status === OrderInformations::STATUS_CUSTOMER_PAID) {
            $newStatus = OrderInformations::STATUS_ORDER_FAIL;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ trả tiền cọc cho người đặt xe";
            //send sms to ng đặt hàng
            $title = $customer->name . " sđt ".  $customer->phone . " Đã hủy chuyến hàng từ " . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến " . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name
                    . " của bạn và hệ thống sẽ trả tiền cọc trong ít giờ tới.";
            //$this->sendSMS($link, $title, $customer->phone);
            //event hoàn lại tiền cho ng đặt
            //send mail to tai xe
            $driver->notify(new SuggestTruckForDriver($link, $title));
            //notification table
            CustomerNotification::create([
                'title' => $title,
                'notification_avatar' => $customer->avatar,
                'link' => $link,
                'customer_id' => $driver->id,
            ]);
        }
        if ($orderInformation->status == OrderInformations::STATUS_WATTING_DRIVER_RECIEVE) {
            $newStatus = OrderInformations::STATUS_CUSTOMER_CANCEL;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ hủy đơn hàng";
        }
        if ($orderInformation->status == OrderInformations::STATUS_DRIVER_ACCEPT ||
                $orderInformation->status == OrderInformations::STATUS_BOTH_ACCEPT) {
            $newStatus = OrderInformations::STATUS_CUSTOMER_CANCEL;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ hủy đơn hàng";
            $title = $customer->name . " sđt ".  $customer->phone . " Đã hủy chuyến hàng từ " . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến " . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name;
            $driver->notify(new SuggestTruckForDriver($link, $title));
        }
        if (!empty($newStatus)) {
            $orderInformation->update([
                "status" => $newStatus,
            ]);
        }

        return [true, $message];
    }

    public function acceptCustomerBookOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        $orderInformation->update([
            "status" => OrderInformations::STATUS_BOTH_ACCEPT,
        ]);

        return [true, "Khách hàng đã đồng ý và sẽ tiến hành thanh toán trong 30 phút tới"];
    }

    public function listOrder()
    {
        $customer = Auth::user();
        $orderInformation = $customer->orderInformation;
        dd($orderInformation->bookTruckInformation);
    }

}
