<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryTruck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryTruckController extends BaseController
{
    public function __construct()
    {
        $this->category_truck = new CategoryTruck();
    }

    public function index()
    {
        $category_truck = $this->category_truck->all();
        return $this->withData($category_truck, 'List Category Truck');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $categoryTruck = $this->category_truck->create([
            'name' => $request['name'],
            'slug' => Str::slug($request['name'], '-'),
        ]);

        return $this->withData($categoryTruck, 'Create category_truck successfully!', 201);
    }

    public function show($id)
    {
        $categoryTruck = $this->category_truck->findOrFail($id);
        return $this->withData($categoryTruck, 'Category Truck Detail');
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $categoryTruck = $this->category_truck->findOrFail($id);
        $categoryTruck->update([
            'name' => $request['name'],
            'slug' => Str::slug($request['name'], '-'),
        ]);

        return $this->withData($categoryTruck, 'Category truck has been updated!');
    }

    public function destroy($id)
    {
        $categoryTruck = $this->category_truck->findOrFail($id)->delete();

        return $this->withSuccessMessage('Category Truck has been deleted!');
    }
}
