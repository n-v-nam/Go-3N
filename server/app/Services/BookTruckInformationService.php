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
use Illuminate\Support\Facades\Log;
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
        $post = $this->post->findOrFail($postId);
        $oldPostStatus =  $post->status;
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
                'link' => $link,
                'customer_id' => $this->post->findOrFail($postId)->truck->customer->id,
            ]);
            //send message to driver sms
            //$this->sendSMS($link, $title, $driver->phone);
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
                UPDATE order_informations SET status = $statusDriverRefuse WHERE order_information_id = $orderInformation->order_information_id and status = $statusWatingDriverRecieve;";

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
        $post = $orderInformation->post;
        $oldPostStatus = $post->status;
        if ($post->status == Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN || $post->status == Post::STATUS_VAN_NHAN_GHEP_HANG) {
            $post->update([
                'status' => Post::STATUS_HIEN_THI_CHUA_NHAN_HANG,
            ]);
        }
        if ($post->status ==  Post::STATUS_WEIGHT_FULL) {
            $post->update([
                'status' => Post::STATUS_VAN_NHAN_GHEP_HANG,
            ]);
        }

        $customer = Auth::user();
        $driver = $post->truck->customer;
        $driverBalance = $driver->balance;
        $newStatus = $message = $title = "";
        $link = "http://localhost:8080/driver/?order-information=" . $orderInformation->order_information_id;
        if ($orderInformation->status === OrderInformations::STATUS_CUSTOMER_PAID ||
            $orderInformation->status === OrderInformations::STATUS_DRIVER_DELIVERED) {
                $newStatus = OrderInformations::STATUS_ORDER_FAIL;
                $message = "Bạn đã hủy chuyến và chúng tôi sẽ trả tiền cọc cho người đặt xe";
                //send sms to ng đặt hàng
                $title = $customer->name . " sđt ".  $customer->phone . " Đã hủy chuyến hàng từ "
                        . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến "
                        . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name
                        . " của bạn và hệ thống sẽ trả tiền cọc trong ít giờ tới.";
                //$this->sendSMS($link, $title, $customer->phone);
                //event trả tiền cọc cho tài xế
                $driver->update([
                    "balance" => $driverBalance + 200000,
                ]);
                if (!empty($driver->email_verified_at)) {
                    $driver->notify(new SuggestTruckForDriver($link, $title));
                }
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
            $title = $customer->name . " sđt ".  $customer->phone . " Đã hủy chuyến hàng từ "
                        . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến "
                        . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name;

            if (!empty($driver->email_verified_at)) {
                $driver->notify(new SuggestTruckForDriver($link, $title));
            }
            CustomerNotification::create([
                'title' => $title,
                'notification_avatar' => $customer->avatar,
                'link' => $link,
                'customer_id' => $driver->id,
            ]);
        }

        if (!empty($newStatus)) {
            $orderInformation->update([
                "status" => $newStatus,
            ]);
        }

        return [true, $message];
    }

    public function acceptDriver($orderInformationId)
    {
        $customer = Auth::user();
        $oldBalnace = $customer->balance;
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        $post =  $orderInformation->post;
        $bookTruckInformation = $orderInformation->bookTruckInformation;
        $driver = $orderInformation->post->truck->customer;
        $link = "http://localhost:8080/driver/?order-information=" . $orderInformation->order_information_id;
        $title = $customer->name . " sđt ".  $customer->phone . " Đã đặt cọc chuyến hàng từ "
                    . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến "
                    . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name;
        DB::beginTransaction();
        try {
            $orderInformation->update([
                "status" => OrderInformations::STATUS_CUSTOMER_PAID,
            ]);
            $customer->update([
                "balance" => $oldBalnace - 200000,
            ]);

            if (!empty($driver->email_verified_at)) {
                $driver->notify(new SuggestTruckForDriver($link, $title));
            }
            $customerNotification = $this->customerNotification->create([
                'title' => $title,
                'notification_avatar' => $customer->avatar,
                'link' => $link,
                'customer_id' => $driver->id,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Khách hàng đã đồng ý và sẽ tiến hành thanh toán trong 30 phút tới"];
    }

    public function listOrder($orderType)
    {
        $arrayStatus = array();
        if ($orderType == 1) { //order chưa dặt cọc hoặc chưa có tài xế nhận chuyến
            array_push($arrayStatus, OrderInformations::STATUS_WATTING_DRIVER_RECIEVE,
                OrderInformations::STATUS_DRIVER_ACCEPT, OrderInformations::STATUS_BOTH_ACCEPT);
        }
        if ($orderType == 2) { //đang giao
            array_push($arrayStatus, OrderInformations::STATUS_CUSTOMER_PAID);
        }
        if ($orderType == 3) { //đã giao
            array_push($arrayStatus, OrderInformations::STATUS_COMPLETED);
        }
        if ($orderType == 4) { //đã bị hủy
            array_push($arrayStatus, OrderInformations::STATUS_ORDER_FAIL,
                OrderInformations::STATUS_CUSTOMER_CANCEL, OrderInformations::STATUS_DRIVER_CANCEL_AFTER_BOTH_ACCPET);
        }
        $customer = Auth::user();
        $orderInformations = $customer->orderInformation->whereIn("status", $arrayStatus)->count() > 0 ?
                                $customer->orderInformation->whereIn("status", $arrayStatus)->get() : null;
        $data = array();
        foreach($orderInformations as $k => $orderInformation) {
            $data[$k]['order_information_id'] = $orderInformation->order_information_id;
            $data[$k]['order_code'] = $orderInformation->code_order;
            $data[$k]['book_information_id'] = $orderInformation->bookTruckInformation->book_truck_information_id;
            $data[$k]['weight'] = $orderInformation->bookTruckInformation->weight_product;
            $data[$k]['item_type'] = $orderInformation->bookTruckInformation->itemType->name;
            $data[$k]['price'] = $orderInformation->bookTruckInformation->price;    //giá  mong muốn
            $data[$k]['from_city'] = $orderInformation->bookTruckInformation->fromCity->name;
            $data[$k]['to_city'] = $orderInformation->bookTruckInformation->toCity->name;
            $data[$k]['count'] = $orderInformation->bookTruckInformation->count;
            $data[$k]['width'] = $orderInformation->bookTruckInformation->width;
            $data[$k]['length'] = $orderInformation->bookTruckInformation->length;
            $data[$k]['height'] = $orderInformation->bookTruckInformation->height;
            $data[$k]['status'] = $orderInformation->status;
            $data[$k]['post_id'] = $orderInformation->post_id;
        }

        return  [true,
                    !$data? null : array_values($data)
                ];
    }

    public function viewOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        if ($orderInformation->bookTruckInformation->customer->id !== Auth::user()->id) {
            return [false, "Bạn không có quyền xem đơn đặt hàng này"];
        }
        $dataOrder = [
            'order_information_id' => $orderInformationId,
            'driver_name' => ($orderInformation->status == OrderInformations::STATUS_DRIVER_ACCEPT ||
                                $orderInformation->status == OrderInformations::STATUS_BOTH_ACCEPT) ?
                                $orderInformation->post->truck->customer->name : null,
            'driver_phone' => ($orderInformation->status == OrderInformations::STATUS_DRIVER_ACCEPT ||
                                $orderInformation->status == OrderInformations::STATUS_BOTH_ACCEPT) ?
                                $orderInformation->post->truck->customer->phone : null,
            'weight' => $orderInformation->bookTruckInformation->weight_product,
            'item_type' => $orderInformation->bookTruckInformation->itemType->name,
            'price' => $orderInformation->bookTruckInformation->price,    //giá  mong muốn
            'from_city' => $orderInformation->bookTruckInformation->fromCity->name,
            'to_city' => $orderInformation->bookTruckInformation->toCity->name,
            'count' => $orderInformation->bookTruckInformation->count,
            'width' => $orderInformation->bookTruckInformation->width,
            'length' => $orderInformation->bookTruckInformation->length,
            'height' => $orderInformation->bookTruckInformation->height,
            'status' => $orderInformation->status,
        ];

        return [true, $dataOrder];
    }

    public function completedOrder($orderInformationId)
    {
        $orderInformation =  $this->orderInformation->findOrFail($orderInformationId);
        $post = $orderInformation->post;
        $customer = Auth::user();
        $driver = $post->truck->customer;
        $statusNhanChuyen = Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN;
        $statusGhepHang = Post::STATUS_VAN_NHAN_GHEP_HANG;
        $statusCompleted =  OrderInformations::STATUS_COMPLETED;
        $statusDriverDelivered =  OrderInformations::STATUS_DRIVER_DELIVERED;
        $link = "http://localhost:8080/driver/?order-information=" . $orderInformation->order_information_id;   //trang chủ
        $title = $driver->name . "sđt".  $driver->phone . " Đã xác nhận nhận hàng chuyến từ "
                    . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến "
                    . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name;
        DB::beginTransaction();
        try {
            $orderInformation->update([
                "recieve_at" => !empty($post->recieve_at) ? $post->recieve_at : Carbon::now(),
                "status" => OrderInformations::STATUS_DRIVER_DELIVERED,
                "completed_at" => Carbon::now()
            ]);
            CustomerNotification::create([
                'title' => $title,
                'notification_avatar' => $customer->avatar,
                'link' => $link,
                'customer_id' => $driver->id,
            ]);
            if (!empty($driver->email_verified_at)) {
                $customer->notify(new SuggestTruckForDriver($link, $title));
            }
            $q =  "CREATE EVENT IF NOT EXISTS update_status_event_$orderInformation->order_information_id
                ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 3 MINUTE
                DO
                UPDATE order_informations SET status = $statusCompleted WHERE order_information_id = $orderInformation->order_information_id and status = $statusDriverDelivered;";

            DB::unprepared($q);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }
    }

}
