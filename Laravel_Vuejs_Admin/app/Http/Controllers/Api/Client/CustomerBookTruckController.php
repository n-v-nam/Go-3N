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

        list($status, $data) = $this->postService->searchPost($request);
        if (!$status) {
            return $this->withData('', 'Không có bài viết viết nào phù hợp');
        } else {
            $params = $request->all();
            $params['customer_id'] = Auth::user()->id;
            $params['status'] = BookTruckInformation::STATUS_PENDING;
            BookTruckInformation::create($params);
        }

        return $this->withData($data, "Kết quả tìm kiếm bài viết");
    }

    public function bookTruck($postId, Request $request)
    {
        list($status, $data) = $this->bookTruckInformationService->bookTruck($postId, $request['post_id']);
        if (!$status) {
            return $this->sendError($data);
        }

        return $this->withSuccessMessage($data);
    }

}
