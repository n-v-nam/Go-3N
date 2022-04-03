<?php

namespace App\Services;
use App\Services\Contracts\PostServiceInterface;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostItemType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\City;
use App\Models\DistanceCityVN;
use Illuminate\Support\Arr;
use Nette\Utils\Arrays;

class PostService implements PostServiceInterface
{
    public function __construct()
    {
        $this->post = new Post();
        $this->postImage = new PostImage();
        $this->postItemType = new PostItemType();
        $this->distanceCityVn = new DistanceCityVN();
        Carbon::setLocale('vi');
    }

    public function store(Request $param)
    {
        $post = $this->post->create([
            'truck_id' => $param['truck_id'],
            'title' => $param['title'],
            'content' => $param['content'] ?? null,
            'from_city_id' => $param['from_city_id'],
            'to_city_id' => $param['to_city_id'],
            'post_type' => $param['post_type'],
            'weight_product' => $param['weight_product'] ?? null,
            'lowest_price' => $param['lowest_price'] ?? null,
            'highest_price' => $param['highest_price'] ?? null,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDay($param['time_display']),
            'is_approve' => true,
            'is_approve_at' => Carbon::now(),
            'user_id' => Auth::user()->id,
            'status' => 1,
        ]);

        if ($post) {
            foreach($param['item_type_id'] as $k => $itemTypeId) {
                $postItemType = $this->postItemType->create([
                    'post_id' => $post->post_id,
                    'item_type_id' => $itemTypeId,
                ]);
            }
            if ($param->hasFile('image')) {
                $images = array();
                foreach($param->file('image') as $key => $images) {
                    $imageName= $images->getClientOriginalName();
                    $path = $images->storeAs('public/photos/post', $imageName);
                    $linkImage = url('/') . Storage::url($path);
                    $postImage = $this->postImage->create([
                        'post_id' => $post->post_id,
                        'image_name' => $linkImage,
                        'link_image' => $linkImage,
                    ]);
                }
            }
        }

        $end_date = new Carbon($post->end_date);
        $dataImage = array();
        $dataItem = array();
        $location_now_at = new Carbon($post->truck->findOrFail($post->truck_id)->location_now_at);
        foreach($post->image as $k => $images) {
            $dataImage[$k] = $images->link_image;
        }
        foreach($post->itemtype as $k => $itemType) {
            $dataItem[$k] = $itemType->name ?? null;
        }

        $datas = [
            'post_information' => [
                'title' => $post->title,
                'content' => $post->content ?? null,
                'from_city' => $post->fromCity->name,
                'to_city' => $post->toCity->name,
                'post_type' => $post->post_type,
                'weight_product' => $post->weight_product,
                'price_number' => $post->lowest_price && $post->highest_price ? "Từ " . $this->currency_format($post->lowest_price) . " đến " . $this->currency_format($post->highest_price) : "thỏa thuận",
                'price' => $post->lowest_price && $post->highest_price ? "Từ " . $this->convert_number_to_words($post->lowest_price) . ' đồng' . " đến " . $this->convert_number_to_words($post->highest_price) . ' đồng': "thỏa thuận",
                'end_date' => $end_date->diffForHumans(Carbon::now()),
                'post_image' => $dataImage,
                'post_item_type' => $dataItem,
            ],
            'truck_information' => [
                'truck_id' => $post->truck->truck_id,
                'license_plates' => $post->truck->license_plates,
                'customer_id' => $post->truck->customer_id,
                'category_truck' => $post->truck->categoryTruck->name ?? null,
                'name' => $post->truck->name,
                'width' => $post->truck->width ?? null,
                'length' => $post->truck->length ?? null,
                'height' => $post->truck->height ?? null,
                'weight' => $post->truck->weight,
                'weight_items' => $post->truck->weight_items,
                'count_order' => $post->truck->count_order,
                'location_city' => $post->truck->city->name ?? null,
                'location_now_at' => $post->truck->location_now_at ? $location_now_at->diffForHumans(Carbon::now()) : null,
            ],
            'customer_information' => [
                'name' => $post->truck->customer->name,
                'phone' => $post->truck->customer->phone,
                'sex' => $post->truck->customer->sex == Customer::HUMAN ? "Nam" : "Nữ",
            ]
        ];

        return [true, $datas];
    }

    public function listPost($isApprove, $status, Request $request)
    {
        $baseQuery = DB::table('post')->join('truck', 'post.truck_id', '=', 'truck.truck_id')
            ->join('customers', 'truck.customer_id', '=', 'customers.id')
            ->select('post_id', 'from_city_id', 'to_city_id', 'phone', 'license_plates', 'title', 'end_date', 'is_approve',
            'content', 'post_type', 'weight_product', 'lowest_price', 'highest_price', 'truck.location_now_at', 'truck.location_now_city_id');

        $baseQuery = $baseQuery->where('post.is_approve', $isApprove)->where('post.status', $status)
            ->whereNull('post.deleted_at')->whereNull('truck.deleted_at')->whereNull('customers.deleted_at');

        if ($request['phone']) {
            $baseQuery = $baseQuery->where('customers.phone', $request['phone']);
        }
        if ($request['license_plates']) {
            $baseQuery = $baseQuery->where('truck.license_plates', $request['license_plates']);
        }

        $postInformations = $baseQuery->get() ?? null;

        if (!count($postInformations)) {
            return [false, "không có bài viết nào"];
        }
        $post = [];
        foreach($postInformations as $k => $post) {
            $end_date = new Carbon($post->end_date);
            $location_now_at = new Carbon($post->location_now_at);
            $postInformation[$k]['tittle'] = $post->title;
            $postInformation[$k]['content'] = $post->content ?? null;
            $postInformation[$k]['avatar'] = $this->postImage->where('post_id', $post->post_id)->pluck('link_image')->first();
            $postInformation[$k]['from_city'] = City::findOrFail($post->from_city_id)->name;
            $postInformation[$k]['to_city'] = City::findOrFail($post->to_city_id)->name;
            $postInformation[$k]['weight_product'] = $post->weight_product;
            $postInformation[$k]['phone'] = $post->phone;
            $postInformation[$k]['priceNumber'] = $post->lowest_price && $post->highest_price ? "Từ " . $this->currency_format($post->lowest_price) . " đến " . $this->currency_format($post->highest_price) : "thỏa thuận";
            $postInformation[$k]['priceWord'] = $post->lowest_price && $post->highest_price ? "Từ " . $this->convert_number_to_words($post->lowest_price) . ' đồng' . " đến " . $this->convert_number_to_words($post->highest_price) . ' đồng': "thỏa thuận";
            $postInformation[$k]['license_plates'] = $post->license_plates;
            $postInformation[$k]['end_date'] = $end_date->diffForHumans(Carbon::now());
            $postInformation[$k]['is_approve'] = $post->is_approve;
        }
        $dataListPost = [
            'list_post_information' => $postInformation,
            'count_post' => count($postInformations),
        ];

        return [true, $dataListPost];
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);
        $end_date = new Carbon($post->end_date);
        $dataImage = array();
        $dataItem = array();
        $location_now_at = new Carbon($post->truck->findOrFail($post->truck_id)->location_now_at);
        foreach($post->image as $k => $images) {
            $dataImage[$k] = $images->link_image;
        }
        foreach($post->itemtype as $k => $itemType) {
            $dataItem[$k] = $itemType->name ?? null;
        }

        $datas = [
            'post_information' => [
                'title' => $post->title,
                'content' => $post->content ?? null,
                'from_city' => $post->fromCity->name,
                'to_city' => $post->toCity->name,
                'post_type' => $post->post_type,
                'weight_product' => $post->weight_product,
                'lowest_price' => $post->lowest_price ?? null,
                'highest_price' => $post->highest_price ?? null,
                'price_number' => $post->lowest_price && $post->highest_price ? "Từ " . $this->currency_format($post->lowest_price) . " đến " . $this->currency_format($post->highest_price) : "thỏa thuận",
                'price' => $post->lowest_price && $post->highest_price ? "Từ " . $this->convert_number_to_words($post->lowest_price) . ' đồng' . " đến " . $this->convert_number_to_words($post->highest_price) . ' đồng': "thỏa thuận",
                'end_date' => $end_date->diffForHumans(Carbon::now()),
                'post_image' => $dataImage,
                'post_item_type' => $dataItem,
            ],
            'truck_information' => [
                'truck_id' => $post->truck->truck_id,
                'license_plates' => $post->truck->license_plates,
                'customer_id' => $post->truck->customer_id,
                'category_truck' => $post->truck->categoryTruck->name ?? null,
                'name' => $post->truck->name,
                'width' => $post->truck->width ?? null,
                'length' => $post->truck->length ?? null,
                'height' => $post->truck->height ?? null,
                'weight' => $post->truck->weight,
                'weight_items' => $post->truck->weight_items,
                'count_order' => $post->truck->count_order,
                'location_city' => $post->truck->city->name ?? null,
                'location_now_at' => $post->truck->location_now_at ? $location_now_at->diffForHumans(Carbon::now()) : null,
            ],
            'customer_information' => [
                'name' => $post->truck->customer->name,
                'phone' => $post->truck->customer->phone,
                'sex' => $post->truck->customer->sex == Customer::HUMAN ? "Nam" : "Nữ",
            ]
        ];

        return [true, $datas];
    }

    public function update($id, Request $param)
    {
        $post = $this->post->findOrFail($id);
        $postItemTypeOld = $this->postItemType->where('post_id', $id)->delete();
        $postImageOld = $this->postImage->where('post_id', $id)->delete();
        $endDate = new Carbon($post->end_date);
        $postUpdate = $post->update([
            'truck_id' => $post->truck_id,
            'title' => $param['title'],
            'content' => $param['content'] ?? null,
            'from_city_id' => $param['from_city_id'],
            'to_city_id' => $param['to_city_id'],
            'post_type' => $param['post_type'],
            'weight_product' => $param['weight_product'] ?? null,
            'lowest_price' => $param['lowest_price'] ?? null,
            'highest_price' => $param['highest_price'] ?? null,
            'end_date' => $param['time_display'] ? $endDate->addDay($param['time_display']) : $post->end_date,
            'user_id' => $post->post_id,
            'status' => $param['time_display'] ? 1 : $post->status,
        ]);

        if ($postUpdate) {
            foreach($param['item_type_id'] as $k => $itemTypeId) {
                $postItemType = $this->postItemType->create([
                    'post_id' => $post->post_id,
                    'item_type_id' => $itemTypeId,
                ]);
            }
            if ($param->hasFile('image')) {
                $images = array();
                foreach($param->file('image') as $key => $images) {
                    $imageName= $images->getClientOriginalName();
                    $path = $images->storeAs('public/photos/post', $imageName);
                    $linkImage = url('/') . Storage::url($path);
                    $postImage = $this->postImage->create([
                        'post_id' => $post->post_id,
                        'image_name' => $linkImage,
                        'link_image' => $linkImage,
                    ]);
                }
            }
        }

        $dataImage = array();
        $dataItem = array();
        $location_now_at = new Carbon($post->truck->findOrFail($post->truck_id)->location_now_at);
        foreach($post->image as $k => $images) {
            $dataImage[$k] = $images->link_image;
        }
        foreach($post->itemtype as $k => $itemType) {
            $dataItem[$k] = $itemType->name ?? null;
        }

        $datas = [
            'post_information' => [
                'title' => $post->title,
                'content' => $post->content ?? null,
                'from_city' => $post->fromCity->name,
                'to_city' => $post->toCity->name,
                'post_type' => $post->post_type,
                'weight_product' => $post->weight_product,
                'price_number' => $post->lowest_price && $post->highest_price ? "Từ " . $this->currency_format($post->lowest_price) . " đến " . $this->currency_format($post->highest_price) : "thỏa thuận",
                'price' => $post->lowest_price && $post->highest_price ? "Từ " . $this->convert_number_to_words($post->lowest_price) . ' đồng' . " đến " . $this->convert_number_to_words($post->highest_price) . ' đồng': "thỏa thuận",
                'end_date' => $endDate->diffForHumans(Carbon::now()),
                'post_image' => $dataImage,
                'post_item_type' => $dataItem,
            ],
            'truck_information' => [
                'truck_id' => $post->truck->truck_id,
                'license_plates' => $post->truck->license_plates,
                'customer_id' => $post->truck->customer_id,
                'category_truck' => $post->truck->categoryTruck->name ?? null,
                'name' => $post->truck->name,
                'width' => $post->truck->width ?? null,
                'length' => $post->truck->length ?? null,
                'height' => $post->truck->height ?? null,
                'weight' => $post->truck->weight,
                'weight_items' => $post->truck->weight_items,
                'count_order' => $post->truck->count_order,
                'location_city' => $post->truck->city->name ?? null,
                'location_now_at' => $post->truck->location_now_at ? $location_now_at->diffForHumans(Carbon::now()) : null,
            ],
            'customer_information' => [
                'name' => $post->truck->customer->name,
                'phone' => $post->truck->customer->phone,
                'sex' => $post->truck->customer->sex == Customer::HUMAN ? "Nam" : "Nữ",
            ]
        ];

        return [true, $datas];

    }

    public function searchPost($param)
    {
        $postItemTypeId = $param['item_type_id'];
        $categoryTruckId = $param['category_truck_id'];
        $fromCiyId = $param['from_city_id'];
        $toCityId = $param['to_city_id'];
        $validDistance = true;
        if ($fromCiyId == $toCityId) {
            return [false, "Khoảng cách quá gần"];
        }
        $baseQuery = DB::table('post')->join('truck', 'post.truck_id', '=', 'truck.truck_id')
            ->join('customers', 'truck.customer_id', '=', 'customers.id')
            ->select('post_id', 'from_city_id', 'to_city_id', 'phone', 'license_plates', 'title', 'end_date', 'is_approve',
            'content', 'post_type', 'weight_product', 'lowest_price', 'highest_price', 'truck.location_now_at', 'truck.location_now_city_id');

        $baseQuery = $baseQuery->where('truck.category_truck_id', '=', $categoryTruckId)->where('post.is_approve', 1)
            ->where('post.status', 1)->whereNull('post.deleted_at')->whereNull('truck.deleted_at')->whereNull('customers.deleted_at')
            ->where('post.weight_product', '>', $param['weight_product'])->whereIn('post.post_id', function ($query) use($postItemTypeId) {
                $query->select('post_id')->from((new PostItemType)->getTable())->where('item_type_id', $postItemTypeId);
            });

        $postInformations = $baseQuery->get() ?? null;

            $postInformation = array();
            foreach($postInformations as $k => $post) {
                $end_date = new Carbon($post->end_date);
                $location_now_at = new Carbon($post->location_now_at);
                $sideTriangle1 = $this->getDistance($post->from_city_id, $fromCiyId);
                $sideTriangle2 = $this->getDistance($fromCiyId, $toCityId);
                $sideTriangle3 = $this->getDistance($toCityId, $post->to_city_id);
                $sideTriangle4 = $this->getDistance($post->from_city_id, $toCityId);
                $sideTriangle5 = $this->getDistance($fromCiyId, $post->to_city_id);
                $sideTriangle6 = $this->getDistance($post->from_city_id, $post->to_city_id);
                if ($this->checkOrderDistance($sideTriangle1, $sideTriangle2, $sideTriangle4) &&
                    $this->checkOrderDistance($sideTriangle2, $sideTriangle3, $sideTriangle5) &&
                    $this->checkValidDistance($sideTriangle1, $sideTriangle2, $sideTriangle3, $sideTriangle6)) {
                        $postInformation[$k]['tittle'] = $post->title;
                        $postInformation[$k]['content'] = $post->content ?? null;
                        $postInformation[$k]['avatar'] = $this->postImage->where('post_id', $post->post_id)->pluck('link_image')->first();
                        $postInformation[$k]['from_city'] = City::findOrFail($post->from_city_id)->name;
                        $postInformation[$k]['to_city'] = City::findOrFail($post->to_city_id)->name;
                        $postInformation[$k]['weight_product'] = $post->weight_product;
                        $postInformation[$k]['phone'] = $post->phone;
                        $postInformation[$k]['priceNumber'] = $post->lowest_price && $post->highest_price ? "Từ " . $this->currency_format($post->lowest_price) . " đến " . $this->currency_format($post->highest_price) : "thỏa thuận";
                        $postInformation[$k]['priceWord'] = $post->lowest_price && $post->highest_price ? "Từ " . $this->convert_number_to_words($post->lowest_price) . ' đồng' . " đến " . $this->convert_number_to_words($post->highest_price) . ' đồng': "thỏa thuận";
                        $postInformation[$k]['license_plates'] = $post->license_plates;
                        $postInformation[$k]['end_date'] = $end_date->diffForHumans(Carbon::now());
                        $postInformation[$k]['is_approve'] = $post->is_approve;
                }
            }

            if (empty($postInformation)) {
                return [false, "không có bài viết nào"];
            }

            $dataListPost = [
                'list_post_information' => array_values($postInformation),
                'count_post' => count($postInformation),
            ];

            return [true, $dataListPost];
    }

    function currency_format($number, $suffix = 'vnđ') {
        if (!empty($number)) {
            return number_format($number, 0, ',', '.') . "{$suffix}";
        }
    }

    function convert_number_to_words($number) {

        $hyphen      = ' ';
        $conjunction = '  ';
        $separator   = ' ';
        $negative    = 'âm ';
        $decimal     = ' phẩy ';
        $dictionary  = array(
        0                   => 'Không',
        1                   => 'Một',
        2                   => 'Hai',
        3                   => 'Ba',
        4                   => 'Bốn',
        5                   => 'Năm',
        6                   => 'Sáu',
        7                   => 'Bảy',
        8                   => 'Tám',
        9                   => 'Chín',
        10                  => 'Mười',
        11                  => 'Mười một',
        12                  => 'Mười hai',
        13                  => 'Mười ba',
        14                  => 'Mười bốn',
        15                  => 'Mười năm',
        16                  => 'Mười sáu',
        17                  => 'Mười bảy',
        18                  => 'Mười tám',
        19                  => 'Mười chín',
        20                  => 'Hai mươi',
        30                  => 'Ba mươi',
        40                  => 'Bốn mươi',
        50                  => 'Năm mươi',
        60                  => 'Sáu mươi',
        70                  => 'Bảy mươi',
        80                  => 'Tám mươi',
        90                  => 'Chín mươi',
        100                 => 'trăm',
        1000                => 'ngàn',
        1000000             => 'triệu',
        1000000000          => 'tỷ',
        1000000000000       => 'nghìn tỷ',
        1000000000000000    => 'ngàn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
        return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
        'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
        E_USER_WARNING
        );
        return false;
        }

        if ($number < 0) {
        return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
        case $number < 21:
        $string = $dictionary[$number];
        break;
        case $number < 100:
        $tens   = ((int) ($number / 10)) * 10;
        $units  = $number % 10;
        $string = $dictionary[$tens];
        if ($units) {
        $string .= $hyphen . $dictionary[$units];
        }
        break;
        case $number < 1000:
        $hundreds  = $number / 100;
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
        if ($remainder) {
        $string .= $conjunction . $this->convert_number_to_words($remainder);
        }
        break;
        default:
        $baseUnit = pow(1000, floor(log($number, 1000)));
        $numBaseUnits = (int) ($number / $baseUnit);
        $remainder = $number % $baseUnit;
        $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
        if ($remainder) {
        $string .= $remainder < 100 ? $conjunction : $separator;
        $string .= $this->convert_number_to_words($remainder);
        }
        break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
        $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
        }

        return $string;
        }

    public function getDistance($fromCiyId, $toCityId)
    {
        $distance = $this->distanceCityVn->where('from_city_id', $fromCiyId)->where('to_city_id', $toCityId)->firstOrFail()->distance;

        return $distance;
    }

    public function checkOrderDistance($sideTriangle1, $sideTriangle2, $sideTriangle3)
    {
        if ($sideTriangle3 >= $sideTriangle1 && $sideTriangle3 >= $sideTriangle2) {
            return true;
        }

        return false;
    }

    public function checkValidDistance($sideTriangle1, $sideTriangle2, $sideTriangle3, $sideTriangle4)
    {
        if ($sideTriangle1 + $sideTriangle2 + $sideTriangle3 > $sideTriangle4*1.5) {
            return false;
        }

        return true;
    }

}
