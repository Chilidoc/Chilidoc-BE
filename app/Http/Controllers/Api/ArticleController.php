<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helper\ResponseHelper;
use App\Models\Article;
use \Auth;

class ArticleController extends Controller
{
    public function index(Request $request) {
        try{
            $data = Article::all();
            return ResponseHelper::sendResponse($data, 'Article Berhasil diBuat!', 200);
        }catch(\Exception $ex){
           return ResponseHelper::rollback($ex);
        }
    }

    public function detail(Request $request,$id) {
        try{
            $data = Article::find($id);
            return ResponseHelper::sendResponse($data, 'Article Berhasil diBuat!', 200);
        }catch(\Exception $ex){
           return ResponseHelper::rollback($ex);
        }
    }

    public function delete(Request $request,$id) {
        try{
            $data = Article::find($id)->delete();
            return ResponseHelper::sendResponse($data, 'Article Berhasil diHapus!', 200);
        }catch(\Exception $ex){
           return ResponseHelper::rollback($ex);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
	        'title' => 'required',
	        'content' => 'required',
	    ], [
            'title.required' => 'Kolom judul harus diisi!',
            'content.required' => 'Kolom konten harus diisi!',
        ]);
	    if ($validator->fails()) {
            return ResponseHelper::sendError($validator->errors()->all()[0], 422);
	    }
	    $data = $validator->validated();
        try{
            $article = Article::insert($data);
            return ResponseHelper::sendResponse($article, 'Article Berhasil diBuat!', 200);
        }catch(\Exception $ex){
           return ResponseHelper::rollback($ex);
        }
    }

    public function update(Request $request,$id) {
        $validator = Validator::make($request->all(), [
	        'title' => 'required',
	        'content' => 'required',
	    ], [
            'title.required' => 'Kolom judul harus diisi!',
            'content.required' => 'Kolom konten harus diisi!',
        ]);
	    if ($validator->fails()) {
            return ResponseHelper::sendError($validator->errors()->all()[0], 422);
	    }
	    $data = $validator->validated();
        try{
            $article = Article::where("id",$id)->update($data);
            return ResponseHelper::sendResponse($article, 'Article Berhasil diUbah!', 200);
        }catch(\Exception $ex){
           return ResponseHelper::rollback($ex);
        }
    }



}
