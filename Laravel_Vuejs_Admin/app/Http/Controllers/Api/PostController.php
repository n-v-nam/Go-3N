<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Support\Facades\Validator;

class PostController extends BaseController
{
    public function __construct(PostServiceInterface $postService)
    {
        $this->post = new Post();
        $this->postService = $postService;
    }

    public function index()
    {
        $post = $this->post->all();
        return $this->withData($post, 'List post');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'license_plates' => 'required|unique:truck,license_plates|max:255',
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
        $truck = $this->post->create([
            'license_plates' => $request['license_plates'],
            'customer_id' => $request['customer_id'],
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
}
