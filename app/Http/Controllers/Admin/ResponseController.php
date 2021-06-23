<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller {

    public function get() {

        $responses = Response::with('messages')->where('user_id', auth()->user()->id)->get();

        $responses = $responses->map(function ($response) {
            $service = Service::find($response->service_id);

            $response->service = [
                'title' => $service->title,
                'address' => $service->address,
                'price' => $service->price
            ];

            return $response;
        });

        return response()->json($responses);
    }

    public function set(Request $request) {

        $validator = Validator::make($request->all(), [
            'service_id' => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $response = new Response($request->all());
        $response->user_id = auth()->user()->id;
        $response->save();

        return response('', 201);
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'service_id' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $response = Response::find($id);

        if (is_null($response)) {
            return response('', 404);
        } else {
            $response->fill($request->all());
            $response->save();
        }

        return response('', 200);
    }

    public function delete($id) {

        $response = Response::find($id);

        if (is_null($response)) {
            return response('', 404);
        } else {
            $response->delete();
        }

        return response('', 200);
    }
}
