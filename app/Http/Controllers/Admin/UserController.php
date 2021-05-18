<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller {

    public function get() {
        return response()->json(auth()->user(), 200);
    }
}
