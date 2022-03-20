<?php

namespace App\Services\Contracts;

use KesmenEnver\ServiceLayer\ServiceInterface;
use Nette\Utils\Arrays;

interface TruckServiceInterface
{
    public function show($id);
    public function update(array $params, $id);
}
