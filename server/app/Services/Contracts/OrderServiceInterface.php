<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;


interface OrderServiceInterface
{
    public function index();
    public function show($id);
    public function update($id, array $params);
}
