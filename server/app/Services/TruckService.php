<?php

namespace App\Services;
use App\Services\Contracts\TruckServiceInterface;
use App\Models\Truck;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TruckService implements TruckServiceInterface
{
    public function __construct()
    {
        $this->truck = new Truck();
        $this->customer = new Customer();
        Carbon::setLocale('vi'); // hiển thị ngôn ngữ tiếng việt.
    }

    public function listTruck($status)
    {
        $trucks = $this->truck->has('customer')->where('status', $status)->get();
        if (!count($trucks)) {
            return [false, "không có xe viết nào"];
        }
        $listTruck = [];
        foreach ($trucks as $key => $truck) {
            $listTruck[$key]['truck_id'] = $truck->truck_id;
            $listTruck[$key]['truck_name'] = $truck->name;
            $listTruck[$key]['category_truck'] = $truck->categoryTruck->name;
            $listTruck[$key]['license_plates'] = $truck->license_plates;
            $listTruck[$key]['phone'] = $truck->customer->phone;
            $listTruck[$key]['weight'] = $truck->weight;
            $listTruck[$key]['weight_items'] = $truck->weight_items;
            $listTruck[$key]['location_now_city'] = $truck->city->name ?? null;
            $listTruck[$key]['location_now_at'] = $truck->location_now_city_id ? $truck->location_now_at : null;
            $listTruck[$key]['count_order'] = $truck->count_order;
            $listTruck[$key]['status'] = $truck->status;
        }
        $dataListTruck = array_values($listTruck);

        return [true, $dataListTruck];

    }

    public function show($id)
    {
        $truck = $this->truck->findOrFail($id);
        $location_now_at = new Carbon($truck->location_now_at);
        $customerInformation = $truck->customer;
        $datas = [
            'truck_information' => [
                'truck_id' => $truck->truck_id,
                'license_plates' => $truck->license_plates,
                'license_plates_image' => $truck->license_plates_image ?? null,
                'customer_id' => $truck->customer_id,
                'category_truck_id' => $truck->category_truck_id,
                'category_truck' => $truck->categoryTruck->name ?? null,
                'name' => $truck->name,
                'width' => $truck->width ?? null,
                'length' => $truck->length ?? null,
                'height' => $truck->height ?? null,
                'weight' => $truck->weight,
                'weight_items' => $truck->weight_items,
                'count_order' => $truck->count_order,
                'location_city' => $truck->city->name ?? null,
                'location_now_at' => $truck->location_now_at ? $location_now_at->diffForHumans(Carbon::now()) : null,
            ],
            'customer_information' => [
                'name' => $customerInformation->name,
                'phone' => $customerInformation->phone,
                'sex' => $customerInformation->sex == Customer::HUMAN ? "Nam" : "Nữ",
            ]
        ];

        return [true, $datas];
    }

    public function update(Request $params, $id)
    {
        $truck = $this->truck->findOrFail($id);
        $linkLicensePlatesImage = "";
        if ($params->hasFile('license_plates_image')) {
            $feature_image_name= $params['license_plates_image']->getClientOriginalName();
            $path = $params->file('license_plates_image')->storeAs('public/photos/truck', $feature_image_name);
            $linkLicensePlatesImage = url('/') . Storage::url($path);
        }
        if($params['license_plates_image'] == $truck->license_plates_image) {
            $linkLicensePlatesImage = $truck->license_plates;
        }
        $customerInformation = $truck->customer ?? null;
        $truckUpdate = $truck->update([
            'license_plates' => $params['license_plates'] ? $params['license_plates'] : $truck->license_plates,
            'license_plates_image' => $linkLicensePlatesImage,
            'customer_id' => $params['customer_id'],
            'category_truck_id' => $params['category_truck_id'],
            'name' => $params['name'],
            'width' => $params['width'],
            'length' => $params['length'],
            'height' => $params['height'],
            'weight' => $params['weight'],
            'weight_items' => $params['weight_items'],
            'status' => $truck->status,
            'user_id_accept' => $truck->user_id_accept,
            'verified_at' => $truck->verified_at ?? null,
            'location_now_city_id' => $params['location_now_city_id'] ?? $truck->location_now_city_id,
            'location_now_at' => isset($params['location_now_city_id']) ? Carbon::now() : $truck->location_now_at,
        ]);
        $location_now_at = new Carbon($this->truck->findOrFail($id)->location_now_at);
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
                'location_now_at' => $truck->location_now_at ? $location_now_at->diffForHumans(Carbon::now()) : null,
            ],
            'customer_information' => [
                'name' => $customerInformation->name,
                'phone' => $customerInformation->phone,
                'sex' => $customerInformation->sex == Customer::HUMAN ? "Nam" : "Nữ",
            ]
        ];

        return [true, $datas];
    }

    public function search($params)
    {
        $baseQuery = $this->truck->join('customers', 'truck.customer_id', 'customers.id');
        if ($params['license_plates']) {
            $baseQuery = $baseQuery->where('truck.license_plates', $params['license_plates']);
        }
        if ($params['phone']) {
            $baseQuery = $baseQuery->where('customers.phone', $params['phone']);
        }
        $trucks = $baseQuery->get();

        if (!count($trucks)) {
            return [false, "không có xe viết nào"];
        }
        $listTruck = [];
        foreach ($trucks as $key => $truck) {
            $listTruck[$key]['truck_id'] = $truck->truck_id;
            $listTruck[$key]['truck_name'] = $truck->name;
            $listTruck[$key]['category_truck'] = $truck->categoryTruck->name;
            $listTruck[$key]['license_plates'] = $truck->license_plates;
            $listTruck[$key]['phone'] = $truck->customer->phone;
            $listTruck[$key]['weight'] = $truck->weight;
            $listTruck[$key]['weight_items'] = $truck->weight_items;
            $listTruck[$key]['location_now_city'] = $truck->city->name ?? null;
            $listTruck[$key]['location_now_at'] = $truck->location_now_city_id ? $truck->location_now_at : null;
            $listTruck[$key]['count_order'] = $truck->count_order;
            $listTruck[$key]['status'] = $truck->status;
        }
        $dataListTruck = array_values($listTruck);

        return [true, $dataListTruck];
    }

}
