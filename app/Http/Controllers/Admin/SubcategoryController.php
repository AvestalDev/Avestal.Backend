<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller {

    public function set(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $subcategory = new Subcategory($request->all());
        $subcategory->save();

        return response('', 201);
    }

    public function getAll() {
        return response()->json(Subcategory::all());
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $subcategory = Subcategory::find($id);

        if (is_null($subcategory)) {
            return response('', 404);
        } else {
            $subcategory->fill($request->all());
            $subcategory->save();
        }

        return response('', 200);
    }

    public function delete($id) {

        $subcategory = Subcategory::find($id);

        if (is_null($subcategory)) return response('', 404);

        $items = Item::where('subcategory_id', $subcategory->id)->count();

        if ($items > 0) return response('', 409);

        $subcategory->delete();

        return response('', 200);
    }
}
