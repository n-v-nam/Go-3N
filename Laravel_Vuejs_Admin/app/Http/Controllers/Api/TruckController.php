<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use Illuminate\Support\Facades\Validator;
use App\Services\Contracts\TruckServiceInterface;
use Illuminate\Support\Facades\Storage;

class TruckController extends BaseController
{
    public function __construct(TruckServiceInterface $truckService)
    {
        $this->truck = new Truck();
        $this->truckService = $truckService;
    }

    public function listTruck($status)
    {
        list($status, $datas) = $this->truckService->listTruck($status);
        if (!$status) {
            return $this->withData('', "Null");
        }
        return $this->withData($datas, 'List Truck');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'license_plates' => 'required|unique:truck,license_plates|max:255',
            'license_plates_image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'customer_id' => 'required',
            'category_truck_id' => 'required',
            'name' => 'max:255',
            'width' => 'numeric|min:1.5|max:5',
            'length' => 'numeric|min:5|max:50',
            'height' => 'numeric|min:2|max:8',
            'weight' => 'required|numeric|min:1|max:30',
            'weight_items' => 'required|numeric|min:5|max:100',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $linkLicensePlatesImage = "";
        if ($request->hasFile('license_plates_image')) {
            $feature_image_name= $request['license_plates_image']->getClientOriginalName();
            $path = $request->file('license_plates_image')->storeAs('public/photos/truck', $feature_image_name);
            $linkLicensePlatesImage = url('/') . Storage::url($path);
        }
        $truck = $this->truck->updateOrCreate([
            'customer_id' => $request['customer_id']
        ], [
            'license_plates' => $request['license_plates'],
            'license_plates_image' => $linkLicensePlatesImage,
            'category_truck_id' => $request['category_truck_id'],
            'name' => $request['name'],
            'width' => $request['width'],
            'length' => $request['length'],
            'height' => $request['height'],
            'weight' => $request['weight'],
            'weight_items' => $request['weight_items'],
            'status' => Truck::STATUS_ENABLE,
            'user_id_accept' => Auth::user()->id,
            'verified_at' => Carbon::now(),
        ]);

        return $this->withData($truck, 'Create truck successfully!', 201);
    }

    public function show($id)
    {
        list($status, $data) = $this->truckService->show($id);
        if (!$status) {
            return $this->sendError('Get truck information fail!');
        }
        return $this->withData($data, 'Truck Detail');
    }

    public function update(Request $request, $id)
    {
        $arrayRequest = array(
            'license_plates' => 'required|max:255',
            'customer_id' => 'required',
            'category_truck_id' => 'required',
            'name' => 'max:255',
            'width' => 'numeric|min:1.5|max:5',
            'length' => 'numeric|min:5|max:50',
            'height' => 'numeric|min:2|max:8',
            'weight' => 'required|numeric|min:1|max:30',
            'weight_items' => 'required|numeric|min:5|max:100',
        );
        if (isset($request['location_now_city_id'])) {
            $arrayRequest['location_now_city_id'] = 'required';
        }

        $validated = Validator::make($request->all(), $arrayRequest);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        list($status, $data) = $this->truckService->update($request, $id);

        return $this->withData($data, 'Truck detail updated!');
    }

    public function destroy($id)
    {
        $truck = $this->truck->findOrFail($id)->delete();

        return $this->withSuccessMessage('Truck has been deleted!');
    }

    public function search(Request $request) {
        $arrayRequest = array();
        if ($request['license_plates']) {
            $arrayRequest['licence_plates'] = 'max:9';
        }
        if ($request['phone']) {
            $arrayRequest['phone'] = 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20';
        }
        $validated = Validator::make($request->all(), $arrayRequest);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        list($status, $datas) = $this->truckService->search($request);
        if (!$status) {
            return $this->withData([], 'Không có xe nào');
        }

        return $this->withData($datas, 'Kết quả.');
    }

    public function getCityName()
    {
        dd(City::pluck('name', 'city_id')->toArray());
        return $this->withData(City::all(), 'All city VN');
    }

    public function isApproveTruck($id)
    {
        $truck = $this->truck->findOrFail($id);
        $truckUpdate = $truck->update([
            'status' => Truck::STATUS_ENABLE,
            'user_id_accept' => Auth()->user()->id,
            'verified_at' => Carbon::now(),
        ]);
    }

}
