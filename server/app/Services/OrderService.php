<?php

namespace App\Services;
use App\Services\Contracts\OrderServiceInterface;
use App\Models\BookTruckInformation;
use App\Models\OrderInformations;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function __construct()
    {
        $this->orderInformation = new OrderInformations();
        $this->bookTruckInformation = new BookTruckInformation();
    }

    public function index()
    {
        $orders = $this->orderInformation->all();
        $data = array();
        foreach($orders as $k => $order) {
            $data[$k]['order_information_id'] = $order->order_information_id;
            $data[$k]['customer_avatar'] = $order->bookTruckInformation->customer->avatar ?? null;
            $data[$k]['customer_phone'] = $order->bookTruckInformation->customer->phone;
            $data[$k]['driver_phone'] = $order->post->truck->customer->phone ?? null;
            $data[$k]['post_id'] = $order->post_id;
            $data[$k]['from_city'] = $order->bookTruckInformation->fromCity->name;
            $data[$k]['to_city'] = $order->bookTruckInformation->toCity->name;
            $data[$k]['weight_product'] = $order->bookTruckInformation->weight_product;
            $data[$k]['item_type'] = $order->bookTruckInformation->itemType->name;
            $data[$k]['status'] = $order->status;
        }

        return [true, array_values($data)];
    }

    public function show($id)
    {
        $orderInformation = $this->orderInformation->findOrFail($id);
        $bookTruckInformation = $orderInformation->bookTruckInformation;
        $post = $orderInformation->post;
        $data = [
            "order_information_id" => $orderInformation->order_information_id,
            "customer_name" => $bookTruckInformation->customer->name ?? null,
            "customer_phone" => $bookTruckInformation->customer->phone,
            "driver_name" => $post->truck->customer->name ?? null,
            "driver_phone" => $post->truck->customer->phone,
            "status" => $orderInformation->status,
            "book_truck_information" => [
                "from_city" => $bookTruckInformation->fromCity->name,
                "to_city" => $bookTruckInformation->toCity->name,
                "item_type" => $bookTruckInformation->itemType->name,
                "category_truck" => $bookTruckInformation->categoryTruck->name,
                "weight_product" => $bookTruckInformation->weight_product,
                "width" => $bookTruckInformation->width ?? null,
                "length" => $bookTruckInformation->length ?? null,
                "height" => $bookTruckInformation->height ?? null,
                "count" => $bookTruckInformation->count ?? null,
                "price" => $bookTruckInformation->price ?? null,
            ]
        ];

        return [true,  $data];
    }

    public function update($id, $params)
    {
        $orderInformation = $this->orderInformation->findorFail($id);
        $post =  $orderInformation->post;
        $bookTruckInformation = $orderInformation->bookTruckInformation;
        $oldStatus = $orderInformation->status;
        $oldCompletedAt = $orderInformation->completed_at ?? null;
        DB::beginTransaction();
        try {
            $orderInformation->update([
                "status" => !empty($params['status']) ? $params['status'] : $oldStatus,
                "completed_at" => !empty($params['completed_at']) ? $params['completed_at'] : $oldCompletedAt,
            ]);
            $post->update([
                "from_city_id" => $bookTruckInformation->to_city_id
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, $e->getMessage()];
        }

        return [true, "Đã cập nhật đơn hàng"];
    }
}
