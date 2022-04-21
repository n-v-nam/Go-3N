<?php

namespace App\Services;
use App\Services\Contracts\DriverServiceInterface;
use App\Models\BookTruckInformation;
use App\Models\Customer;
use App\Models\OrderInformations;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;
use App\Models\City;
use App\Notifications\SuggestTruckForDriver;
use App\Models\CustomerNotification;
use Illuminate\Support\Facades\Cookie;
use App\Services\BaseService;
use App\Jobs\sendSMSDriverUnresponsive1;
use App\Models\SuggestTruck;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DriverService extends BaseService implements DriverServiceInterface
{
    public function __construct()
    {
        $this->customer = new Customer();
        $this->post = new Post();
        $this->bookTruckInformation = new BookTruckInformation();
        $this->orderInformation = new OrderInformations();
        $this->suggestTruck = new SuggestTruck();
    }

    public function viewOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        if ($this->post->findOrFail($orderInformation->post_id)->truck->customer->id !== Auth::user()->id) {
            return [false, "Bạn không có quyền xem đơn đặt hàng này"];
        }
        if ($orderInformation->status == OrderInformations::STATUS_CUSTOMER_CANCEL) {
            return [false , "Người đặt đã hủy đơn hàng này trước đó"];
        }
        if ($orderInformation->status === OrderInformations::STATUS_DRIVER_REFUSE) {
            return [false, "Đã quá thời gian và hệ thống đã chuyển đơn hàng sang cho tài xế khác"];
        }
        if ($orderInformation->status === OrderInformations::STATUS_ORDER_FAIL) {
            return [false, "Đã quá thời gian và đơn hàng đã bị hủy"];
        }
        if ($orderInformation->status === OrderInformations::STATUS_CUSTOMER_CANCEL_AFTER_DRIVER_ACCEPT) {
            return [false, "Khách hàng đã hủy đơn và chúng tôi sẽ hoàn tiền cọc lại cho bạn trong ít giờ tới"];
        }
        if ($orderInformation->status === OrderInformations::STATUS_DRIVER_CANCEL_AFTER_BOTH_ACCPET) {
            return [false, "Bạn đã hủy đơn hàng này và hệ thống đã chuyển sang cho tài xế khác"];
        }

        $dataOrder = [
            'order_information_id' => $orderInformationId,
            'customer_name' => $orderInformation->bookTruckInformation->customer->name,
            'customer_phone' => $orderInformation->bookTruckInformation->customer->phone,
            'weight' => $orderInformation->bookTruckInformation->weight_product,
            'item_type' => $orderInformation->bookTruckInformation->itemType->name,
            'price' => $orderInformation->bookTruckInformation->price,    //giá  mong muốn
            'from_city' => $orderInformation->bookTruckInformation->fromCity->name,
            'to_city' => $orderInformation->bookTruckInformation->toCity->name,
            'count' => $orderInformation->bookTruckInformation->count,
            'width' => $orderInformation->bookTruckInformation->width,
            'length' => $orderInformation->bookTruckInformation->length,
            'height' => $orderInformation->bookTruckInformation->height,
            'order_status' => $orderInformation->status,
        ];

        return [true, $dataOrder];
    }

    public function acceptCustomerBookOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        $post = $orderInformation->post;
        $customer = $orderInformation->bookTruckInformation->customer;
        $driver = Auth::user();
        if ($this->post->findOrFail($orderInformation->post_id)->truck->customer->id !== $driver->id) {
            return [false, "Bạn không có quyền xem đơn đặt hàng này"];
        }
        if ($orderInformation->status === OrderInformations::STATUS_CUSTOMER_PAID) {
            //gọi function return tiền cọc cho ng đặt
        }
        if ($orderInformation->status === OrderInformations::STATUS_WATTING_DRIVER_RECIEVE) {
            DB::beginTransaction();
            try {
                $orderInformation->update([
                    "status" => OrderInformations::STATUS_DRIVER_ACCEPT,
                ]);
                $post->update([
                    "status" => Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN,
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return [false, $e->getMessage()];
            }
            //send sms to ng đặt hàng
            $link = "http://localhost:8080/customer-book-truck/?order-information=" . $orderInformation->order_information_id;
            $title = $driver->name . $driver->phone . " Đã nhận chuyến hàng từ " . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến " . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name . " của bạn đặt trước đó";
            //$this->sendSMS($link, $title, $customer->phone);
            //send mail to ng đặt
            if (!empty($customer->email_verified_at)) {
                $customer->notify(new SuggestTruckForDriver($link, $title));
            }
            //notification table
            CustomerNotification::create([
                'title' => $title,
                'notification_avatar' => Auth::user()->avatar,
                'link' => $link,
                'customer_id' => $customer->id,
            ]);
            //process payment
            return [true, "Sau khi khách hàng xác nhận đơn hàng trên hệ thống sẽ giữ tiền đặt cọc cho bạn"];
        }

        return [false, "Đã xảy ra lỗi,hãy load lại trang!"];
    }

    public function driverCancelOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        $post = $this->post->findOrFail($orderInformation->post_id);
        $post->update([
            'status' => Post::STATUS_HIEN_THI_CHUA_NHAN_HANG,
        ]);
        $customer = $orderInformation->bookTruckInformation->customer;
        $driver = Auth::user();
        $params = !unserialize(Cookie::get("book_truck_information_id" . $orderInformation->book_truck_information_id)) ? null : unserialize(Cookie::get("book_truck_information_id" . $orderInformation->book_truck_information_id));
        $driverIdSuggestTrucks = !empty($this->driverSuggestTrucks($orderInformation->post_id, $params)) ? $this->driverSuggestTrucks($orderInformation->post_id, $params) : null;
        $newStatus = $message = "";
        if ($orderInformation->status === OrderInformations::STATUS_BOTH_ACCEPT) {
            $newStatus = OrderInformations::STATUS_DRIVER_CANCEL_AFTER_BOTH_ACCPET;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ hoàn lại tiền cọc cho người đặt xe";
            //send sms to ng đặt hàng
            $link = "http://localhost:8080/client-customer/payment/?order-information=" . $orderInformation->order_information_id;   //trang chủ
            $title = $driver->name . "sđt".  $driver->phone . " Đã hủy chuyến hàng từ " . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến " . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name
                    . " của bạn và hệ thống sẽ hoàn lại tiền cọc trong ít giờ tới.";
            //$this->sendSMS($link, $title, $customer->phone);
            //evrnt hoàn lại tiền cho ng đặt
            //send mail to ng đặt
            $customer->notify(new SuggestTruckForDriver($link, $title));
            //notification table
            CustomerNotification::create([
                'title' => $title,
                'notification_avatar' => Auth::user()->avatar,
                'link' => $link,
                'customer_id' => $customer->id,
            ]);
        }
        if ($orderInformation->status === OrderInformations::STATUS_WATTING_DRIVER_RECIEVE) {
            $newStatus = OrderInformations::STATUS_DRIVER_REFUSE;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ chuyến chuyến xe sang cho tài xế khác";
        }
        //suggest Truck
        if (!empty($driverIdSuggestTrucks)) {
            dispatch(new sendSMSDriverUnresponsive1($orderInformation, $driverIdSuggestTrucks, $customer))->delay(Carbon::now()->addMinutes(2));
        }
        if (!empty($newStatus)) {
            $orderInformation->update([
                'status' => $newStatus,
            ]);
        }

        return [true, $message];
    }

    public function viewSuggest($suggestTruckId)
    {
        $suggestTruck = $this->suggestTruck->findOrFail($suggestTruckId);
        $bookTruckInformation = $suggestTruck->bookTruckInformation;
        $customer = $suggestTruck->bookTruckInformation->customer;
        $driver = $suggestTruck->post->truck->customer;
        $checkPermission = false;
        if ($driver->id === AUth::user()->id) {
            $checkPermission = true;
        }
        if (!$checkPermission) {
            return [false, "Bạn không có quyền"];
        }
        if ($suggestTruck->bookTruckInformation->status == BookTruckInformation::STATUS_BOTH_ACCEPT) {
            return [false, "Đã có tài xế khác nhận chuyến trước bạn"];
        }

        $data = [
            'book_information_id' => $bookTruckInformation->book_truck_information_id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'weight' => $bookTruckInformation->weight_product,
            'item_type' => $bookTruckInformation->itemType->name,
            'price' => $bookTruckInformation->price,    //giá  mong muốn
            'from_city' => $bookTruckInformation->fromCity->name,
            'to_city' => $bookTruckInformation->toCity->name,
            'count' => $bookTruckInformation->count,
            'width' => $bookTruckInformation->width,
            'length' => $bookTruckInformation->length,
            'height' => $bookTruckInformation->height,
            'book_truck_information_status' => $bookTruckInformation->status,
        ];

        return [true, $data];
    }

    public function acceptSuggestTruck($suggestTruckId)
    {
        $suggestTruck = $this->suggestTruck->findOrFail($suggestTruckId);
        $post = $suggestTruck->post;
        $bookTruckInformation = $suggestTruck->bookTruckInformation;
        $customer = $suggestTruck->bookTruckInformation->customer;
        $driver = $suggestTruck->post->truck->customer;
        $checkPermission = false;
        if ($driver->id === AUth::user()->id) {
            $checkPermission = true;
        }
        if (!$checkPermission) {
            return [false, "Bạn không có quyền"];
        }
        if ($suggestTruck->bookTruckInformation->status == BookTruckInformation::STATUS_DRIVER_SUGGEST_ACCEPT) {
            return [false, "Đã có tài xế khác nhận chuyến"];
        }
        DB::beginTransaction();
        try {
            $bookTruckInformation->update([
                "status" => BookTruckInformation::STATUS_DRIVER_SUGGEST_ACCEPT,
            ]);
            $orderInformation = $this->createOrder($customer, $suggestTruck->post_id, $suggestTruck->book_truck_information_id, OrderInformations::STATUS_DRIVER_ACCEPT);
            $post->update([
                "status" => Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN,
            ]);
            $statusBothAccept = OrderInformations::STATUS_BOTH_ACCEPT;
            $statusDriverAccept = OrderInformations::STATUS_DRIVER_ACCEPT;
            $statusDriverRefuse = OrderInformations::STATUS_DRIVER_REFUSE;
            $statusHienThi = Post::STATUS_HIEN_THI_CHUA_NHAN_HANG;
            $q =  "CREATE EVENT IF NOT EXISTS update_status_event_$orderInformation->order_information_id
                ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 MINUTE
                DO
                UPDATE order_informations SET status = $statusDriverRefuse WHERE order_information_id = $orderInformation->order_information_id and status NOT IN ($statusBothAccept);
                UPDATE post SET status = $statusHienThi WHERE post_id = $post->post_id and STATUS = $statusDriverAccept;";

            DB::unprepared($q);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Hãy liên hệ với người đặt để biết thêm chi tiết"];
    }

}
