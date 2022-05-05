<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface BookTruckInformationServiceInterface
{
    public function bookTruck($postId);
    public function customerCancelOrder($orderInformationId);
    public function acceptDriver($orderInformationId);
    public function listOrder($orderType);
    public function viewOrder($orderInformationId);
    public function completedOrder($orderInformationId);
}
