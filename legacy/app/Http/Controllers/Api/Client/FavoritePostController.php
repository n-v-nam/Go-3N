<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FavoritePost;
use App\Http\Controllers\Api\BaseController;
use App\Models\City;
use Carbon\Carbon;
use App\Services\Contracts\PostServiceInterface;

class FavoritePostController extends BaseController
{
    public function __construct(PostServiceInterface $postService)
    {
        $this->favoritePost = new FavoritePost();
        $this->postService = $postService;
    }

    public function store(Request $request)
    {
        $param = $request["post_id"];
        $favoritePost = $this->favoritePost->create([
            "customer_id" => Auth::user()->id,
            "post_id" => $param
        ]);

        return $this->withSuccessMessage("Da Luu");
    }

    public function index()
    {
        $customerId = Auth::user()->id;
        $favoritePosts = $this->favoritePost->where("customer_id", $customerId)->get() ?? null;
        $data = [];
        foreach ($favoritePosts as $key => $favoritePost) {
            $location_now_at = new Carbon($favoritePost->post->truck->location_now_at);
            $data[$key]['post_id'] = $favoritePost->post->post_id;
            $data[$key]['tittle'] = $favoritePost->post->title;
            $data[$key]['from_city'] = $favoritePost->post->fromCity->name;
            $data[$key]['to_city'] = $favoritePost->post->toCity->name;
            $data[$key]['weight_product'] = $favoritePost->post->weight_product;
            $data[$key]['priceNumber'] = $favoritePost->post->lowest_price && $favoritePost->post->highest_price ? "Từ " .
                $this->postService->currency_format($favoritePost->post->lowest_price) . " đến " .
                $this->postService->currency_format($favoritePost->post->highest_price) : "thỏa thuận";
            $data[$key]['location_now_city'] = City::find($favoritePost->post->location_now_city_id)->name ?? null;
            $data[$key]['location_now_at'] = $favoritePost->post->location_now_at ? $location_now_at->diffForHumans(Carbon::now()) : null;
        }

        return $this->withData(array_values($data), "Danh sach yeu thich post");
    }

    public function destroy($id)
    {
        $favoritePost = $this->favoritePost->findOrFail($id);
        $favoritePost->delete();

        return $this->withSuccessMessage("Da Xoa");
    }
}
