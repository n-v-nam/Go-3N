<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemTypeController extends BaseController
{
    public function __construct()
    {
        $this->item_type = new ItemType();
    }

    public function index()
    {
        $item_type = $this->item_type->all();
        return $this->withData($item_type, 'List Item Type');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $itemType = $this->item_type->create([
            'name' => $request['name'],
            'slug' => Str::slug($request['name'], '-'),
        ]);

        return $this->withData($itemType, 'Create item_type successfully!', 201);
    }

    public function show($id)
    {
        $itemType = $this->item_type->findOrFail($id);
        return $this->withData($itemType, 'Item Type Detail');
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $itemType = $this->item_type->findOrFail($id);
        $itemType->update([
            'name' => $request['name'],
            'slug' => Str::slug($request['name'], '-'),
        ]);

        return $this->withData($itemType, 'Item type has been updated!');
    }

    public function destroy($id)
    {
        $itemType = $this->item_type->findOrFail($id)->delete();

        return $this->withSuccessMessage('Item type has been deleted!');
    }
}
