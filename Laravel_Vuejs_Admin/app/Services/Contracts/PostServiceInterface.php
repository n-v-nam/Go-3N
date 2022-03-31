<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;
use KesmenEnver\ServiceLayer\ServiceInterface;
use Nette\Utils\Arrays;

interface PostServiceInterface
{
    public function listPost($isApprove, $status, Request $request);
    public function store(Request $param);
    public function show($id);
    public function update($id, Request $request);
}
