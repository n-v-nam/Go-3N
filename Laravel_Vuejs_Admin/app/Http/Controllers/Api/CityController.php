<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\City;
use App\Models\District;

class CityController extends BaseController
{
    public function __construct()
    {
        $this->city = new City();
        $this->district = new District();
    }

    public function getCity()
    {
        $city = $this->city->pluck("name", "city_id")->toArray();
        return $this->withData($city, "Danh sách các tỉnh");
    }

    public function getDistrict($cityId)
    {
        $district = $this->district->where("city_id", $cityId)->pluck("name", "district_id")->toArray();

        return $this->withData($district, "Danh sách quận huyện");
    }
}
