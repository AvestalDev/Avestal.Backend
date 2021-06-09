<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller {

    public function image(Request $request) {

        $validator = Validator::make($request->all(), [
            'image' => ['required', 'mimes:jpg,jpeg,png,webp', 'max:500000']
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $path = $request->file('image')->store('photo', 'public');

        return response()->json([
            'link' => "https://api.avestal.ru/storage/{$path}"
        ], 200);
    }

    public function images(Request $request) {

        $validator = Validator::make($request->all(), [
            'images' => 'required',
            'images.*' => [ 'mimes:jpg,jpeg,png,webp']
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $links = [];

        if ($request->hasfile('images')) {
            foreach($request->file('images') as $file) {
                $path = $file->store('photo', 'public');
                $links[] = "https://api.avestal.ru/storage/{$path}";
            }
        }

        return response()->json([
            'links' => $links
        ], 200);
    }

    public function files(Request $request) {

        $validator = Validator::make($request->all(), [
            'files' => 'required',
            'files.*' => [ 'mimes:pdf,txt,doc,docs,xls,docx']
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 422);

        $links = [];

        if ($request->hasfile('files')) {
            foreach($request->file('files') as $file){
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs("file", $filename, 'public');
                $links[] = "https://api.avestal.ru/storage/{$path}";
            }
        }

        return response()->json([
            'links' => $links
        ], 200);
    }

}
