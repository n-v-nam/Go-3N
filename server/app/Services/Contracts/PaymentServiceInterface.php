<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface PaymentServiceInterface
{
    public function addMoney(array $params);
    public function saveBill(array $params);
}
