<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {

    public function set(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $category = new Category($request->all());
        $category->save();

        return response('', 201);
    }

    public function getAll() {
        return response()->json(Category::with('subcategories')->get());
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $category = Category::find($id);

        if (is_null($category)) {
            return response('', 404);
        } else {
            $category->fill($request->all());
            $category->save();
        }

        return response('', 200);
    }

    public function delete($id) {

        $category = Category::find($id);

        if (is_null($category)) return response('', 404);

        $items = Item::where('category_id', $category->id)->count();

        if ($items > 0) return response('', 409);

        $subcategories = Subcategory::where('category_id', $category->id)->count();

        if ($subcategories > 0) return response('', 403);

        $category->delete();

        return response('', 200);
    }
}
