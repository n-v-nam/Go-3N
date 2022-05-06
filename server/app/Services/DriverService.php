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
use App\Models\Truck;
use App\Notifications\SuggestTruckForDriver;
use App\Models\CustomerNotification;
use Illuminate\Support\Facades\Cookie;
use App\Services\BaseService;
use App\Jobs\sendSMSDriverUnresponsive1;
use App\Models\SuggestTruck;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMoneyDriver;

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
        if ($orderInformation->status === OrderInformations::STATUS_ORDER_FAIL) {
            return [false, "Đơn hàng thất bại, không thể xem !"];
        }
        if ($orderInformation->status === OrderInformations::STATUS_CUSTOMER_CANCEL_AFTER_DRIVER_ACCEPT) {
            return [false, "Khách hàng đã hủy đơn và chúng tôi sẽ hoàn tiền cọc lại cho bạn trong ít giờ tới"];
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
            'status' => $orderInformation->status,
        ];

        return [true, $dataOrder];
    }

    public function acceptCustomerBookOrder($orderInformationId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformationId);
        $post = $orderInformation->post;
        $bookTruckInformation =  $orderInformation->bookTruckInformation;
        $customer = $orderInformation->bookTruckInformation->customer;
        $driver = Auth::user();
        if ($this->post->findOrFail($orderInformation->post_id)->truck->customer->id !== $driver->id) {
            return [false, "Bạn không có quyền xem đơn đặt hàng này"];
        }
        // if ($orderInformation->status === OrderInformations::STATUS_CUSTOMER_PAID) {
        //     //gọi function return tiền cọc cho ng đặt
        // }
        if ($orderInformation->status == OrderInformations::STATUS_WATTING_DRIVER_RECIEVE) {
            DB::beginTransaction();
            try {
                $orderInformation->update([
                    "status" => OrderInformations::STATUS_DRIVER_ACCEPT,
                ]);
                //update status post
                if ($post->status == Post::STATUS_HIEN_THI_CHUA_NHAN_HANG &&
                    $post->post_type == Post::POST_TYPE_KHONG_GHEP_HANG) {
                    $post->update([
                        'status' => Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN,
                    ]);
                }
                if ($post->status == Post::STATUS_HIEN_THI_CHUA_NHAN_HANG &&
                    $post->post_type == Post::POST_TYPE_GHEP_HANG &&
                    $post->weight_product > $bookTruckInformation->weight_product + 10) {
                        $post->update([
                            'status' => Post::STATUS_VAN_NHAN_GHEP_HANG,
                        ]);
                }
                if ($post->status == Post::STATUS_VAN_NHAN_GHEP_HANG &&
                    $post->post_type == Post::POST_TYPE_GHEP_HANG) {
                        $post->update([
                            'status' => Post::STATUS_WEIGHT_FULL,
                        ]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return [false, $e->getMessage()];
            }
            //send sms to ng đặt hàng
            $link = "http://localhost:8080/customer/?order-information=" . $orderInformation->order_information_id;
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
        $post = $orderInformation->post;
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
        $customer = $orderInformation->bookTruckInformation->customer;
        $customerBalance = $customer->balance;
        $driver = Auth::user();
        $params = !unserialize(Cookie::get("book_truck_information_id" . $orderInformation->book_truck_information_id)) ? null : unserialize(Cookie::get("book_truck_information_id" . $orderInformation->book_truck_information_id));
        $driverIdSuggestTrucks = !empty($this->driverSuggestTrucks($orderInformation->post_id, $params)) ? $this->driverSuggestTrucks($orderInformation->post_id, $params) : null;
        $newStatus = $message = "";
        if ($orderInformation->status === OrderInformations::STATUS_BOTH_ACCEPT) {
            $newStatus = OrderInformations::STATUS_DRIVER_CANCEL_AFTER_BOTH_ACCPET;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ hoàn lại tiền cọc cho người đặt xe";
            //send sms to ng đặt hàng
            $link = "http://localhost:8080/customer/?order-information=" . $orderInformation->order_information_id;   //trang chủ
            $title = $driver->name . "sđt".  $driver->phone . " Đã hủy chuyến hàng từ "
                        . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến "
                        . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name
                        . " của bạn và hệ thống sẽ hoàn lại tiền cọc trong ít giờ tới.";
            DB::beginTransaction();
            try {
                //$this->sendSMS($link, $title, $customer->phone);
                //event hoàn lại tiền cho ng đặt
                $customer->update([
                    "balance" => $customerBalance + 200000,
                ]);
                //send mail to ng đặt
                $customer->notify(new SuggestTruckForDriver($link, $title));
                //notification table
                CustomerNotification::create([
                    'title' => $title,
                    'notification_avatar' => Auth::user()->avatar,
                    'link' => $link,
                    'customer_id' => $customer->id,
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return [false, $e->getMessage()];
            }
        }
        if ($orderInformation->status === OrderInformations::STATUS_WATTING_DRIVER_RECIEVE) {
            $newStatus = OrderInformations::STATUS_DRIVER_REFUSE;
            $message = "Bạn đã hủy chuyến và chúng tôi sẽ chuyến chuyến xe sang cho tài xế khác";
        }
        if ($orderInformation->status === OrderInformations::STATUS_DRIVER_ACCEPT) {
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

        return [$message ? true : false, $message ? 'Bạn đã huỷ thành công !': 'Thao tác sai quy định !'];
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
        $oldPostStatus = $post->status;
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
            //xóa order cũ
            DB::table("order_informations")->where("book_truck_information_id", $bookTruckInformation->book_truck_information_id)->delete();
            $orderInformation = $this->createOrder($customer, $suggestTruck->post_id, $suggestTruck->book_truck_information_id,
                                    OrderInformations::STATUS_DRIVER_ACCEPT);
            $suggestTruck->delete();
            $statusCustomerPaid = OrderInformations::STATUS_CUSTOMER_PAID;
            $statusNhanChuyen = Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN;
            $statusCustomerCancel = OrderInformations::STATUS_CUSTOMER_CANCEL_AFTER_DRIVER_ACCEPT;
            $statusHienThi = Post::STATUS_HIEN_THI_CHUA_NHAN_HANG;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Hãy liên hệ với người đặt để biết thêm chi tiết"];
    }

    public function listOrder($orderType)
    {
        $arrayStatus = array();
        if ($orderType == 1) { //order chưa phản hồi
            array_push($arrayStatus, OrderInformations::STATUS_WATTING_DRIVER_RECIEVE,
                OrderInformations::STATUS_DRIVER_ACCEPT, OrderInformations::STATUS_BOTH_ACCEPT);
        }
        if ($orderType == 2) { //đang giao
            array_push($arrayStatus, OrderInformations::STATUS_CUSTOMER_PAID);
        }
        if ($orderType == 3) { //đã giao
            array_push($arrayStatus, OrderInformations::STATUS_COMPLETED);
        }
        if ($orderType == 4) { //đã huỷ
            array_push($arrayStatus, OrderInformations::STATUS_DRIVER_REFUSE, OrderInformations::STATUS_ORDER_FAIL);
        }

        $driver = Auth::user();
        $driverPostId = array();
        foreach($driver->post as $key => $post) {
            $driverPostId[$key] =  $post->post_id;
        }
        $orderInformations = $this->orderInformation->whereIn("status", $arrayStatus)->whereIn("post_id", $driverPostId)->count() > 0 ?
                                $this->orderInformation->whereIn("status", $arrayStatus)->whereIn("post_id", $driverPostId)->get() : null;
        if ($orderInformations) {
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
            }
        } else {
            $data = null;
        }

        return  [true,
                    !$data ? [] : array_values($data)
                ];
    }

    public function listSuggestTruck()
    {
        $driver = Auth::user();
        $driverPostId = array();
        foreach($driver->post as $key => $post) {
            $driverPostId[$key] =  $post->post_id;
        }
        $suggestTrucks = $this->suggestTruck->whereIn("post_id", $driverPostId)->count() > 0 ?
                            $this->suggestTruck->whereIn("post_id", $driverPostId)->get() : [];
        $data = array();
        foreach($suggestTrucks as $k => $suggestTruck) {
            $data[$k]['suggest_truck_id'] = $suggestTruck->suggest_truck_id;
            $data[$k]['book_information_id'] = $suggestTruck->bookTruckInformation->book_truck_information_id;
            $data[$k]['weight'] = $suggestTruck->bookTruckInformation->weight_product;
            $data[$k]['item_type'] = $suggestTruck->bookTruckInformation->itemType->name;
            $data[$k]['price'] = $suggestTruck->bookTruckInformation->price;    //giá  mong muốn
            $data[$k]['from_city'] = $suggestTruck->bookTruckInformation->fromCity->name;
            $data[$k]['to_city'] = $suggestTruck->bookTruckInformation->toCity->name;
            $data[$k]['count'] = $suggestTruck->bookTruckInformation->count;
            $data[$k]['width'] = $suggestTruck->bookTruckInformation->width;
            $data[$k]['length'] = $suggestTruck->bookTruckInformation->length;
            $data[$k]['height'] = $suggestTruck->bookTruckInformation->height;
            $data[$k]['status'] = $suggestTruck->bookTruckInformation->status;
        }

        return [true, array_values($data)];
    }

    public function completedOrder($orderInformaionId)
    {
        $orderInformation = $this->orderInformation->findOrFail($orderInformaionId);
        $post = $orderInformation->post;
        $customer = $orderInformation->bookTruckInformation->customer;
        $driver = Auth::user();
        $newPostStatus = Post::STATUS_HIEN_THI_CHUA_NHAN_HANG;
        if ($post->status == Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN || $post->status == Post::STATUS_VAN_NHAN_GHEP_HANG) {
            $newPostStatus = Post::STATUS_HIEN_THI_CHUA_NHAN_HANG;
        }
        if ($post->status == Post::STATUS_WEIGHT_FULL) {
            $newPostStatus = Post::STATUS_VAN_NHAN_GHEP_HANG;
        }
        $statusNhanChuyen = Post::STATUS_HIEN_THI_DA_NHAN_CHUYEN;
        $statusGhepHang = Post::STATUS_VAN_NHAN_GHEP_HANG;
        $statusCustomerPaid =  OrderInformations::STATUS_CUSTOMER_PAID;
        $statusDriverDelivered =  OrderInformations::STATUS_DRIVER_DELIVERED;
        $link = "http://localhost:8080/customer/?order-information=" . $orderInformation->order_information_id;   //trang chủ
        $title = $driver->name . "sđt".  $driver->phone . " Đã hoàn thành chuyến hàng từ "
                    . City::findOrFail($orderInformation->bookTruckInformation->from_city_id)->name . " đến "
                    . City::findOrFail($orderInformation->bookTruckInformation->to_city_id)->name
                    . " của bạn, hãy bấm xác nhận.";
        DB::beginTransaction();
        try {
            $orderInformation->update([
                "recieve_at" => !empty($post->recieve_at) ? $post->recieve_at : Carbon::now(),
                "status" => OrderInformations::STATUS_DRIVER_DELIVERED,
            ]);
            CustomerNotification::create([
                'title' => $title,
                'notification_avatar' => $driver->avatar,
                'link' => $link,
                'customer_id' => $customer->id,
            ]);
            if (!empty($customer->email_verified_at)) {
                $customer->notify(new SuggestTruckForDriver($link, $title));
            }
            dispatch(new SendMoneyDriver($orderInformation, $this->customer->findOrFail($driver->id)))->delay(Carbon::now()->addMinutes(3));
            $q =  "CREATE EVENT IF NOT EXISTS update_post_status_event_$post->post_id
                ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 MINUTE
                DO
                UPDATE post SET status = $newPostStatus WHERE post_id = $post->post_id;";

            DB::unprepared($q);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Tài xế đã giao hàng"];
    }

}
