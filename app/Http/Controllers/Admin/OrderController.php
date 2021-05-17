<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function set(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required',
            'price' => 'required',
            'address' => 'required',
            'geolocation' => ['required', 'array'],
            'images' => ['required', 'array'],
            'files' => ['required', 'array'],
            'items' => ['required', 'array'],
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $order = new Order($request->all());
        $order->save();

        return response('', 201);
    }

    public function get($id) {

        $order = Order::find($id);

        if (is_null($id)) return response('', 404);

        return response($order, 200);
    }

    public function getAll() {
        return response()->json(Order::simplePaginate(20));
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required',
            'price' => 'required',
            'address' => 'required',
            'geolocation' => ['required', 'array'],
            'images' => ['required', 'array'],
            'files' => ['required', 'array'],
            'items' => ['required', 'array'],
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $order = Order::find($id);

        if (is_null($order)) {
            return response('', 404);
        } else {
            $order->fill($request->all());
            $order->save();
        }

        return response('', 200);
    }

    public function delete($id) {

        $order = Order::find($id);

        if (is_null($id)) {
            return response('', 404);
        } else {
            $order->delete();
        }

        return response('', 200);
    }
}
