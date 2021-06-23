<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller {

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
            'rating' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $service = new Service($request->all());
        $service->user_id = auth()->user()->id;
        $service->save();

        return response('', 201);
    }

    public function get($id) {

        $service = Service::with('responses')->find($id);

        if (is_null($service)) return response('', 404);

        return response($service, 200);
    }

    public function getAll() {
        $services = Service::with('responses')->simplePaginate(20);

        $services = $this->itemsParse($services);

        return response()->json($services);
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
            'user_id' => ['nullable']
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $service = Service::find($id);

        if (is_null($service)) {
            return response('', 404);
        } else {
            $service->fill($request->all());
            $service->user_id = $request->user_id;
            $service->save();
        }

        return response('', 200);
    }

    public function delete($id) {

        $service = Service::find($id);

        if (is_null($service)) {
            return response('', 404);
        } else {
            $service->delete();
        }

        return response('', 200);
    }

    public function itemsParse($services) {
        foreach ($services as $service) {
            $service->items = Item::whereIn('id', $service->items)->get();
        }

        return $services;
    }
}
