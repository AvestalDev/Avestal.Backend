<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller {

    public function images(Request $request) {

        $validator = Validator::make($request->all(), [
            'image' => ['required', 'mimes:jpg,jpeg,png,webp']
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $path = $request->file('image')->store('photo', 'public');
        
        return response()->json([
            'link' => "https://api.avestal.ru/storage/{$path}"
        ], 200);
    }
}
