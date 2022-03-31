<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Truck;
use App\Models\PostItemType;
use App\Models\PostImage;
use App\Models\City;
use Carbon\Carbon;

class PostController extends BaseController
{
    public function __construct(PostServiceInterface $postService)
    {
        $this->post = new Post();
        $this->postService = $postService;
        $this->postImage = new PostImage();
        $this->postItemType = new PostItemType();
    }

    public function listPost($isApprove, $status, Request $request)
    {
        $validateRequest = [];
        if ($request['phone']) {
            $validateRequest['phone'] = 'required|max:12';
        }
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
            return $this->sendError('Craete Post information fail!');
        }

        return $this->withData($data, 'Post has been created and is waiting for admin approval!', 201);
    }

    public function show($id)
    {
        list($status, $data) = $this->postService->show($id);
        if (!$status) {
            return $this->sendError('This post has been deleted');
        }

        return $this->withData($data, 'Post detail');
    }

    public function updatePost($id, Request $request)
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
            return $this->sendError('Post update failed');
        }

        return $this->withData($data, 'Post update successfully ');
    }

    public function destroy($id)
    {
        $postItemTypeOld = $this->postItemType->where('post_id', $id)->delete();
        $postImageOld = $this->postImage->where('post_id', $id)->delete();
        $post = $this->post->findOrFail($id)->delete();

        return $this->withSuccessMessage('Post deleted successfully!');
    }

    public function isApprovePost($id)
    {
        $post = $this->post->findOrFail($id);
        $isApprovePost = $post->update([
            'is_approve' => 1
        ]);

        return $this->withSuccessMessage('The post has been approved');
    }

}
