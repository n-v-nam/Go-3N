<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;
use Nette\Utils\Arrays;
use Illuminate\Http\Request;

interface TruckServiceInterface
{
    public function show($id);
    public function listTruck($status);
    public function update(Request $params, $id);
    public function search(array $params);
}
