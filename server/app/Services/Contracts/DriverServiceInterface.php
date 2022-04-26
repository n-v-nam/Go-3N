<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface DriverServiceInterface
{
    public function viewOrder($orderInformaionId);
    public function acceptCustomerBookOrder($orderInformaionId);
    public function driverCancelOrder($orderInformaionId);
    public function viewSuggest($suggestTruckId);
    public function acceptSuggestTruck($suggestTruckId);
    public function listOrder($truckId);
    public function listSuggestTruck($truckId);
}
