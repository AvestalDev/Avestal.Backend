<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller {

    public function get($id) {

        $client = User::client()->find($id);

        if (is_null($client)) return response('', 404);

        return response($client, 200);
    }

    public function getAll() {
        return response()->json(User::client()->simplePaginate(20));
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'fio' => ['required', 'array'],
            'data' => ['required', 'array']
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $client = User::client()->find($id);

        if (is_null($client)) {
            return response('', 404);
        } else {
            $client->fill($request->all());
            $client->save();
        }

        return response('', 200);
    }

    public function delete(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $client = User::client()->find($id);

        if (is_null($client)) {
            return response('', 404);
        } else {
            if ($request->status === 'banned') {
                $client->permission = 3;
                $client->save();
            } else if ($request->status === 'unbanned') {
                $client->permission = 1;
                $client->save();
            } else {
                $client->delete();
            }
        }

        return response('', 200);
    }
}
