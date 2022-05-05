<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Truck;
use App\Models\BookTruckInformation;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use App\Services\Contracts\BookTruckInformationServiceInterface;
use App\Services\Contracts\PostServiceInterface;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderInformations;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class CustomerBookTruckController extends BaseController
{
    public function __construct(PostServiceInterface $postService, BookTruckInformationServiceInterface $bookTruckInformation)
    {
        $this->postService = $postService;
        $this->bookTruckInformationService = $bookTruckInformation;
        $this->customer = new Customer();
        $this->truck = new Truck();
        $this->orderInformation = new OrderInformations();
        $this->bookTruckInformation = new BookTruckInformation();
    }

    public function searchPost(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'category_truck_id' => 'required',
            'to_city_id' => 'required',
            'from_city_id' => 'required',
            'item_type_id' => 'required',
            'weight_product' => 'required|numeric|max:100|min:10',
            'price' => 'numeric|min:100000|max:100000000',
            'count' => 'required|numeric',
            'width' => 'required|numeric',
            'length' => 'required|numeric',
            'height' => 'required|numeric',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $params = $request->all();
        $cityVN = config('const.city_vn');
        $checkFromCity = $checkToCity = false;
        foreach ($cityVN as $key => $cityName) {
            if ($cityName == $request["from_city_id"]) {
                $params['from_city_id'] = $key;
                $checkFromCity = true;
            }
            if ($cityName == $request["to_city_id"]) {
                $params['to_city_id'] = $key;
                $checkToCity = true;
            }
        }
        if (!$checkFromCity || !$checkToCity) {
            return $this->withSuccessMessage("Không tìm thấy bài viết");
        }

        list($status, $data) = $this->postService->searchPost($params);
        if (!$status) {
            return $this->withData('', 'Không có bài viết viết nào phù hợp');
        } else {
            $params['customer_id'] = Auth::user()->id;
            $params['status'] = BookTruckInformation::STATUS_PENDING;
            $bookTruckInformation = BookTruckInformation::create($params);
        }

        return $this->withData($data, "Kết quả tìm kiếm bài viết")
            ->withCookie('book_truck_information_id' . $bookTruckInformation->book_truck_information_id, serialize($data["driver_suggest_post_id"]), 2);
    }

    public function viewPost($postId)
    {
        list($status, $data) = $this->postService->show($postId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, 'Thông tin bài viết');
    }

    public function bookTruck($postId)
    {
        list($status, $data) = $this->bookTruckInformationService->bookTruck($postId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function customerCancelOrder($orderInformationId)
    {
        $oderInformation = $this->orderInformation->findOrFail($orderInformationId);
        list($status, $data) = $this->bookTruckInformationService->customerCancelOrder($orderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function acceptDriver($orderInformationId)
    {
        list($status, $data) = $this->bookTruckInformationService->acceptDriver($orderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

    public function listOrder($orderType)
    {
        list($status, $data) = $this->bookTruckInformationService->listOrder($orderType);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data ?? [], "Danh sách order");
    }

    public function viewOrder($orderInformationId)
    {
        list($status, $data) = $this->bookTruckInformationService->viewOrder($orderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "View Order");
    }

    public function completedOrder($orderInformationId)
    {
        list($status, $data) = $this->bookTruckInformationService->completedOrder($orderInformationId);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withData($data, "Hoàn thành đơn hàng");
    }

}
