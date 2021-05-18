<?php

namespace App\Http\Controllers\Auth;

use App\Models\AccessIP;
use App\Models\User;
use App\Models\Whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {

    public function username() {
        return 'phone';
    }

    public function code(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $user = User::where('phone', $request->phone)->first();

        $code = 1111;

        return response()->json([
            'hash' => hash('sha256', $code),
            'auth' => !is_null($user),
        ]);
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        if (!$token = auth()->attempt(['phone' => $request->phone, 'password' => 'Recode200GET'])) {
            return response('', 404);
        } else {
            $count = Whitelist::where('user_id', auth()->user()->id)->count();

            if ($count >= 10) {
                $whitelist = Whitelist::where('user_id', auth()->user()->id)->first();

                $whitelist->delete();
            }

            $this->setIP($request);
            $this->authentication($request, $token);

            return response()->json([
                'access_token' => $token
            ], 200);
        }
    }

    public function authentication($request, $token) {
        $whitelist = new Whitelist;

        $whitelist->user_id = auth()->user()->id;
        $whitelist->access_token = $token;
        $whitelist->ip = $request->ip();
        $whitelist->save();
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'unique:users'],
            'fio' => 'required',
            'data' => 'required',
            'permission' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $user = new User;

        $user->phone = $request->phone;
        $user->password = Hash::make('Recode200GET');
        $user->permission = $request->permission;
        $user->fio = $request->fio;
        $user->data = $request->data;
        $user->save();

        return response('', 201);
    }

    public function setIP($request) {

        $access_ip = AccessIP::where('ip', $request->ip())->first();

        if (is_null($access_ip)) {
            $access_ip = new AccessIP;

            $access_ip->user_id = auth()->user()->id;
            $access_ip->ip = $request->ip();
            $access_ip->save();
        }
    }

    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout() {
        $whitelist = Whitelist::where('access_token', JWTAuth::getToken())->first();

        if (!is_null($whitelist)) {
            $whitelist->delete();
            auth()->logout();
        } else {
            return response('', 404);
        }

        return response()->json([], 200);
    }
}
