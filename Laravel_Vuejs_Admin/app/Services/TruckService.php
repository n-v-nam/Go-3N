<?php

namespace App\Services;
use App\Services\Contracts\TruckServiceInterface;
use App\Models\Truck;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TruckService implements TruckServiceInterface
{
    public function __construct()
    {
        $this->truck = new Truck();
    }

    public function show($id)
    {
        $truck = $this->truck->findOrFail($id);
        $customerInformation = $truck->customer;
        $datas = [
            'truck_information' => [
                'truck_id' => $truck->truck_id,
                'license_plates' => $truck->license_plates,
                'customer_id' => $truck->customer_id,
                'category_truck' => $truck->categoryTruck->name ?? null,
                'name' => $truck->name,
                'width' => $truck->width ?? null,
                'length' => $truck->length ?? null,
                'height' => $truck->height ?? null,
                'weight' => $truck->weight,
                'weight_items' => $truck->weight_items,
                'count_order' => $truck->count_order,
                'location_city' => $truck->city->name ?? null,
                'location_now_at' => $truck->location_now_at ?? null,
            ],
            'customer_information' => [
                'name' => $customerInformation->name,
                'phone' => $customerInformation->phone,
                'sex' => $customerInformation->sex,
            ]
        ];

        return [true, $datas];
    }

    public function update(array $params, $id)
    {
        $truck = $this->truck->findOrFail($id);
        $customerInformation = $truck->customer ?? null;
        $truckUpdate = $truck->update([
            'license_plates' => $params['license_plates'],
            'customer_id' => $params['customer_id'],
            'category_truck_id' => $params['category_truck_id'],
            'name' => $params['name'],
            'width' => $params['width'],
            'length' => $params['length'],
            'height' => $params['height'],
            'weight' => $params['weight'],
            'weight_items' => $params['weight_items'],
            'status' => Truck::STATUS_ENABLE,
            'user_id_accept' => Auth::user()->id,
            'verified_at' => Carbon::now(),
            'location_now_city_id' => $params['location_now_city_id'] ?? $truck->location_now_city_id,
            'location_now_at' => isset($params['location_now_city_id']) ? Carbon::now() : $truck->location_now_at,
        ]);

        $datas = [
            'truck_information' => [
                'truck_id' => $truck->truck_id,
                'license_plates' => $truck->license_plates,
                'customer_id' => $truck->customer_id,
                'category_truck' => $truck->categoryTruck->name ?? null,
                'name' => $truck->name,
                'width' => $truck->width ?? null,
                'length' => $truck->length ?? null,
                'height' => $truck->height ?? null,
                'weight' => $truck->weight,
                'weight_items' => $truck->weight_items,
                'count_order' => $truck->count_order,
                'location_city' => $truck->city->name ?? null,
                'location_now_at' => $truck->location_now_at ?? null,
            ],
            'customer_information' => [
                'name' => $customerInformation->name,
                'phone' => $customerInformation->phone,
                'sex' => $customerInformation->sex,
            ]
        ];

        return [true, $datas];
    }
}
