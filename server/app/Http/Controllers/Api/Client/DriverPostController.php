<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\ItemType;
use App\Models\PostImage;
use App\Models\Truck;
use Illuminate\Support\Facades\Validator;
use App\Services\Contracts\PostServiceInterface;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\BookTruckInformation;
use App\Models\OrderInformations;
use App\Services\Contracts\DriverServiceInterface;

class DriverPostController extends BaseController
{
    public function __construct(PostServiceInterface $postService, DriverServiceInterface $driverService)
    {
        $this->post = new Post();
        $this->postService = $postService;
        $this->bookTruckInformation = new BookTruckInformation();
        $this->orderInformation = new OrderInformations();
        $this->driverService = $driverService;
    }

    public function store(Request $request)
    {
        $validateRequest = [
            'truck_id' => 'required',
            'title' => 'required|max:255',
            'content' => 'max:255',
            'image.*' => 'mimes:jpeg,jpg,png,gif,svg|max:2048',
            'from_city_id' => 'required',
            'to_city_id' => 'required',
            'post_type' => 'required|numeric',
            'weight_product' => 'required|numeric|min:10|max:100',
            'time_display' => 'required|numeric|max:100',
            'item_type_id.*' => 'required',
        ];
        if (!is_null($request['lowest_price'])) {
            $validateRequest['lowest_price'] = 'numeric|min:100000|max:100000000';
        }
        if (!is_null($request['highest_price'])) {
            $validateRequest['highest_price'] = 'numeric|min:100000|max:100000000';
        }
        $validated = Validator::make($request->all(), $validateRequest);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        list($status, $data) = $this->postService->store($request);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, 'Bạn đã tạo bài đăng và chờ admin phê duyệt!', 201);
    }
    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);
        $post->delete();

        return $this->withSuccessMessage("Bạn đã xóa bài viết");
    }

    public function show($id)
    {
        list($status, $data) = $this->postService->show($id);
        if (!$status) {
            return $this->sendError("Lỗi lấy dữ liệu");
        }

        return $this->withData($data, "Thông tin bài viết");
    }

    public function update(Request $request, $id)
    {
        $validateRequest = [
            'truck_id' => 'required',
            'title' => 'required|max:255',
            'content' => 'max:255',
            'image.*' => 'mimes:jpeg,jpg,png,gif,svg|max:2048',
            'from_city_id' => 'required',
            'to_city_id' => 'required',
            'post_type' => 'required|numeric',
            'weight_product' => 'required|numeric|min:10|max:100',
            'time_display' => 'required|numeric|max:100',
            'item_type_id.*' => 'required',
        ];
        if (!is_null($request['lowest_price'])) {
            $validateRequest['lowest_price'] = 'numeric|min:100000|max:100000000';
        }
        if (!is_null($request['highest_price'])) {
            $validateRequest['highest_price'] = 'numeric|min:100000|max:100000000';
        }
        $validated = Validator::make($request->all(), $validateRequest);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        list($status, $data) = $this->postService->update($id, $request);
        if (!$status) {
            return $this->sendError('Cập nhật bài viết không thành công');
        }

        return $this->withData($data, 'Cập nhật bài viết thành công');
    }

    public function listPost($isApprove, $status, Request $request)
    {
        $validateRequest = [];
        if ($request['license_plates']) {
            $validateRequest['license_plates'] = 'required|max:9';
        }
        $validated = Validator::make($request->all(), $validateRequest);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        list($status, $data) = $this->postService->listPost($isApprove, $status, $request);
        if (!$status) {
            return $this->withData('', $data);
        }

        return $this->withData($data, 'List post');
    }

    public function viewOrder($oderInformationId)
    {
        $oderInformation = $this->orderInformation->findOrFail($oderInformationId);
        list($status, $data) = $this->driverService->viewOrder($oderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Thông tin đơn hàng");
    }

    public function acceptCustomerBookOrder($oderInformationId)
    {
        $oderInformation = $this->orderInformation->findOrFail($oderInformationId);
        list($status, $data) = $this->driverService->acceptCustomerBookOrder($oderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function driverCancelOrder($oderInformationId)
    {
        $oderInformation = $this->orderInformation->findOrFail($oderInformationId);
        list($status, $data) = $this->driverService->driverCancelOrder($oderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function viewSuggest($suggestTruckId)
    {
        list($status, $data) = $this->driverService->viewSuggest($suggestTruckId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Thông tin đề xuất chuyến xe");
    }

    public function acceptSuggestTruck($suggestTruckId)
    {
        list($status, $data) = $this->driverService->acceptSuggestTruck($suggestTruckId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function listOrder($orderType)
    {
        list($status, $data) = $this->driverService->listOrder($orderType);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Danh sách đơn hàng của bạn");
    }

    public function listSuggestTruck($truckId)
    {
        list($status, $data) = $this->driverService->listSuggestTruck($truckId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Danh sách đơn hàng");
    }

}
