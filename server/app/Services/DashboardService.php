<?php

namespace App\Services;
use App\Services\Contracts\DashboardServiceInterface;
use App\Models\Customer;
use App\Models\BookTruckInformation;
use App\Models\OrderInformations;
use App\Models\City;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService implements DashboardServiceInterface
{
    public function __construct()
    {
        $this->customer = new Customer();
        $this->orderInformation = new OrderInformations();
        $this->bookTruckInformation = new BookTruckInformation();
        $this->post = new Post();
    }

    public function dashboard(array $params)
    {
        $startDate = "2020-04-29 15:45:52";
        //customer book truck
        $customerBookTrucks = $this->customer->where("customer_type", Customer::CUSTOMER_BOOK_TRUCK)->count();
        $customerBookTruckByTime = $this->customer->where("customer_type", Customer::CUSTOMER_BOOK_TRUCK)
                                    ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])->count();
        $customerBookTruckByTimex2 = $this->customer->where("customer_type", Customer::CUSTOMER_BOOK_TRUCK)
                                        ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"] * 2),
                                        Carbon::now()->subMonths($params["time_type"])])->count();
        $customerBookTruckByTimePercent = !$customerBookTruckByTimex2 ? 100 : ($customerBookTruckByTime / $customerBookTruckByTimex2 - 1) * 100;
        //driver
        $drivers = $this->customer->where("customer_type", Customer::DRIVER)->count();
        $driverByTime = $this->customer->where("customer_type", Customer::DRIVER)
                            ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])->count();
        $driverByTimex2 = $this->customer->where("customer_type", Customer::DRIVER)
                            ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"] * 2),
                            Carbon::now()->subMonths($params["time_type"])])->count();
        $driverByTimePercent = !$driverByTimex2 ? 100 : ($driverByTime / $driverByTimex2 - 1) * 100;
        //book truck search
        $booktruckInformations = $this->bookTruckInformation
                                    ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]),
                                    Carbon::now()])->count();
        $booktruckInformationx2 = $this->bookTruckInformation
                                    ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"] * 2),
                                    Carbon::now()->subMonths($params["time_type"])])->count();
        $booktruckInformationPercent = !$booktruckInformationx2 ? 100 : ($booktruckInformations / $booktruckInformationx2 - 1) * 100;
        $booktruckInformationGroupByCitys = DB::table("book_truck_informations")->select("from_city_id", "to_city_id", DB::raw('count(*) as total'))
                                            ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])
                                            ->groupBy("from_city_id", "to_city_id")->orderBy("total" ,"DESC")->limit(10)->get();
        //search
        $booktruckByCity = array();
        foreach($booktruckInformationGroupByCitys as $key => $booktruckInformationGroupByCity) {
            $booktruckByCity[$key]['from_city'] = City::findOrFail($booktruckInformationGroupByCity->from_city_id)->name;
            $booktruckByCity[$key]['to_city'] = City::findOrFail($booktruckInformationGroupByCity->to_city_id)->name;
            $booktruckByCity[$key]['total'] = $booktruckInformationGroupByCity->total;
        }
        //post
        $posts = $this->post->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])->count();
        $postByTimex2 = $this->post->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"] * 2),
                            Carbon::now()->subMonths($params["time_type"])])->count();
        $postPercent = !$postByTimex2 ? 100 : ($posts / $postByTimex2 - 1) * 100;
        $postByCity = array();
        $postGroupByCitys = DB::table("post")->select("from_city_id", "to_city_id", DB::raw('count(*) as total'))
                        ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])
                        ->groupBy("from_city_id", "to_city_id")->orderBy("total" ,"DESC")->limit(10)->get();
        foreach($postGroupByCitys as $k => $postGroupByCity) {
            $postByCity[$k]['from_city'] = City::findOrFail($postGroupByCity->from_city_id)->name;
            $postByCity[$k]['to_city'] = City::findOrFail($postGroupByCity->to_city_id)->name;
            $postByCity[$k]['total'] = $postGroupByCity->total;
        }
        //order
        $countOrders = $this->orderInformation->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])->count();
        $countOrderComplated = $this->orderInformation->where("status", OrderInformations::STATUS_COMPLETED)->whereBetween("created_at",
                                [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])->count();
        $orderPercentCompleted = ($countOrderComplated / $countOrders - 1) * 100;
        //money
        $moneyByTime = DB::table("customer_bill")
                        ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"]), Carbon::now()])->sum("amount");
        $moneyByTimex2 = DB::table("customer_bill")
                            ->whereBetween("created_at", [Carbon::now()->subMonths($params["time_type"] * 2),
                            Carbon::now()->subMonths($params["time_type"])])->sum("amount");
        $moneyPercent = !$moneyByTimex2 ? 100 : ($moneyByTime / $moneyByTimex2 - 1) * 100;
        //data return
        $data = [
            "customer_dashboard" => [
                "name" => "Thông tin về khách hàng",
                "data" => $customerBookTrucks,
                "by_time" => $customerBookTruckByTime,
                "by_time_x2" => $customerBookTruckByTimex2,
                "by_time_percent" => $customerBookTruckByTimePercent
            ],
            "driver_dashboard" => [
                "name" => "Thông tin về tài xế",
                "data" => $drivers,
                "by_time" => $driverByTime,
                "by_time_x2" => $driverByTimex2,
                "by_time_percent" => $driverByTimePercent
            ],
            "book_truck_dashboard" => [
                "name" => "Thông tin về đặt xe",
                "by_time" => $booktruckInformations,
                "by_time_x2" => $booktruckInformationx2,
                "percent" => $booktruckInformationPercent,
                "by_city" => $booktruckByCity
            ],
            "post" => [
                "name" => "Thông tin về bài đăng",
                "by_time" => $posts,
                "by_time_x2" => $postByTimex2,
                "percent" => $postPercent,
                "by_city" => $postByCity
            ],
            "order_dashboard" => [
                "name" => "Thông tin về đơn hàng",
                "by_time" => $countOrders,
                "by_time_x2" => $countOrderComplated,
                "percent" => $orderPercentCompleted
            ],
            "monney_dashboard" => [
                "name" => "Thông tin về thanh toán",
                "by_time" => $moneyByTime,
                "by_time_x2" => $moneyByTimex2,
                "percent" => $moneyPercent
            ]
        ];

        return [true, $data];
    }

}
