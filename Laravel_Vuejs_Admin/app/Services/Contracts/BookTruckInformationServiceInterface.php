<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface BookTruckInformationServiceInterface
{
    public function bookTruck($postId, array $params);
}
