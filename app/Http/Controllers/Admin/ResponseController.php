<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller {

    public function set(Request $request) {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $response = new Response($request->all());
        $response->user_id = auth()->user()->id;
        $response->save();

        return response('', 201);
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
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
