<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Contracts\TruckServiceInterface;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Truck;
use App\Models\PersonnelNotification;
use Illuminate\Support\Facades\Storage;

class DriverController extends BaseController
{
    public function __construct(TruckServiceInterface $truckService)
    {
        $this->customer = new Customer();
        $this->truck = new Truck();
        $this->truckService = $truckService;
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'license_plates' => 'required|unique:truck,license_plates|max:255',
            'license_plates_image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        DB::beginTransaction();
        try {
            $truck = $this->truck->firstOrCreate([
                'customer_id' => Auth::user()->id,
                'license_plates' => $request['license_plates'],
                'license_plates_image' => $linkLicensePlatesImage,
                'category_truck_id' => $request['category_truck_id'],
                'name' => $request['name'],
                'width' => $request['width'],
                'length' => $request['length'],
                'height' => $request['height'],
                'weight' => $request['weight'],
                'weight_items' => $request['weight_items'],
                'status' => Truck::STATUS_PENDING,
            ]);
            // admin notification
            PersonnelNotification::create([
                'title' => "T??i x??? " . Auth::user()->name . "-" . Auth::user()->phone . " v???a t???o xe v?? ch??? duy???t",
                'notification_avatar' => Auth::user()->avatar ?? null,
                'link' => "",
                'status' => PersonnelNotification::STATUS_UNREAD,
            ]);
            //send mail
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError("L???i l??u th??ng tin");
        }

        return $this->withData($truck, '????ng k?? xe th??nh c??ng!', 201);
    }

    public function index()
    {
        $truck = $this->truck->select('truck.truck_id', 'truck.license_plates', 'truck.name', 'truck.status', 'truck.category_truck_id')->where('truck.customer_id', Auth::user()->id)->get();

        return $this->withData($truck, 'List Truck');
    }

    public function destroy($id)
    {
        $truck = $this->truck->findOrFail($id);
        $truck->delete();

        return $this->withSuccessMessage('X??a truck th??nh c??ng!');
    }

    public function show($id)
    {
        list($status, $data) = $this->truckService->show($id);
        if (!$status) {
            return $this->sendError('L???i th??ng tin');
        }

        return $this->withData($data, 'Th??ng tin xe');
    }

    public function update(Request $request, $id)
    {
        $licensePlates = $this->truck->findOrFail($id)->license_plates;
        if ($this->truck->findOrFail($id)->customer_id !== Auth()->user()->id) {
            return $this->sendError('B???n kh??ng c?? quy???n cho h??nh ?????ng n??y');
        }
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
        if (!$status) {
            return $this->sendError('C???p nh???t kh??ng th??nh c??ng');
        }
        //Send Notification

        PersonnelNotification::create([
            'title' => "L??i xe " . Auth::user()->name . " ???? c???p nh???t th??ng tin xe bi???n s??? " . $licensePlates,
            'notification_avatar' => Auth::user()->avatar ?? null,
            'link' => "",
            'status' => PersonnelNotification::STATUS_UNREAD,
        ]);

        return $this->withData($data, 'C???p nh???t th??nh c??ng!');
    }
}
