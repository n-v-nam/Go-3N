<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface PaymentServiceInterface
{
    public function addMoney($customerId, array $params);
}
