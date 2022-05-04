<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface DashboardServiceInterface
{
    public function dashboard(array $params);
}
