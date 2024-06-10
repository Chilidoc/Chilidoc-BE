<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseHelper;
use App\Models\History;
use \Auth;

class HistoryController extends Controller
{
    public function index(Request $request) {
        try{
            $data = History::where('user_id', Auth::id())->get();
            return ResponseHelper::sendResponse($data, '', 200);
        }catch(\Exception $ex){
           return ResponseHelper::throw($ex);
        }
    }

    public function detail(Request $request,$id) {
        try{
            $data = History::find($id);
            return ResponseHelper::sendResponse($data, '', 200);
        }catch(\Exception $ex){
           return ResponseHelper::throw($ex);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
	        'result' => 'required',
	        'image' => 'required|image|max:5240',
	    ], [
            'result.required' => 'Kolom hasil harus diisi!',
            'image.required' => 'Kolom gambar harus diisi!',
            'image.image' => 'Kolom gambar harus berupa gambar!',
            'image.max' => 'Gambar harus < 5MB!',
        ]);
	    if ($validator->fails()) {
            return ResponseHelper::sendError($validator->errors()->all()[0], 422);
	    }
	    $data = $validator->validated();
        try{
            $disk = Storage::disk('local');
            $file = $disk->put('history', $request->file('image'));
            if($file) {
                $path = '/storage/'.$file;
                $data['image'] = $path;
            }
            $data['user_id'] = Auth::id();
            $history = History::insert($data);
            return ResponseHelper::sendResponse($history, 'History Berhasil diBuat!', 200);
        }catch(\Exception $ex){
           return ResponseHelper::throw($ex);
        }
    }
}
