<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller {

    public function set(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'specification' => ['required', 'array'],
            'price' => 'required',
            'images' => ['required', 'array'],
            'preview_image' => 'required',
            'status' => 'required',
            'type' => 'required',
            'unit_type' => 'required',
            'quantity' => 'required',
            'discount' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $item = new Item($request->all());
        $item->save();

        return response('', 201);
    }

    public function get($id) {

        $item = Item::with('category', 'subcategory')->find($id);

        if (is_null($item)) return response('', 404);

        return response($item, 200);
    }

    public function getAll() {
        return response()->json(Item::with('category', 'category')->simplePaginate(20));
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'specification' => ['required', 'array'],
            'price' => 'required',
            'images' => ['required', 'array'],
            'preview_image' => 'required',
            'status' => 'required',
            'type' => 'required',
            'unit_type' => 'required',
            'quantity' => 'required',
            'discount' => 'required',
            'category_id' => 'required',
            'subcategory_id' => ['required', 'nullable'],
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $item = Item::find($id);

        if (is_null($item)) {
            return response('', 404);
        } else {
            $item->fill($request->all());
            $item->save();
        }

        return response('', 200);
    }

    public function delete($id) {

        $item = Item::find($id);

        if (is_null($item)) {
            return response('', 404);
        } else {
            $item->delete();
        }

        return response('', 200);
    }
}
