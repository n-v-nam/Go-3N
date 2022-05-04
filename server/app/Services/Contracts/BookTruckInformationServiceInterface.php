<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface BookTruckInformationServiceInterface
{
    public function bookTruck($postId);
    public function customerCancelOrder($orderInformationId);
    public function acceptCustomerBookOrder($orderInformationId);
    public function listOrder($orderType);
    public function viewOrder($orderInformationId);
}
