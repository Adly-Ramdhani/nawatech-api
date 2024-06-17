<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $category = Category::with('product')->get();

            return ApiFormatter::sendResponse(200, 'succes', $category);
        }catch(\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'product_id' => 'required',
                'name' => 'required',
                'deskripsikategori' => 'required',
            ]);
    
            $category = Category::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'name' => $request->name,
                'deskripsikategori' => $request->deskripsikategori,
            ]);
    
            return ApiFormatter::sendResponse(200, 'success', $category);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $category = Category::with('product')->find($id);
            return ApiFormatter::sendResponse(200, 'succes', $category);
        } catch(\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, $id)
    {
        try{
            $this->validate($request, [
                'user_id' => 'required',
                'product_id' => 'required',
                'name' => 'required',
                'deskripsikategori' => 'required',
            ]);

            $checkProses = Category::where('id', $id)->update([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'name' => $request->name,
                'deskripsikategori' => $request->deskripsikategori,
            ]);

            if($checkProses) {
                $data = Category::find($id);
                return ApiFormatter::sendResponse(200, 'success', $data);
            }else{
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal mengubah data!');
            }
        }catch(\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, $id)
    {
        try {
            $checkProses = Category::where('id', $id)->delete();

            if ($checkProses) {
                return ApiFormatter::sendResponse(200, 'success', 'Data category berhasil dihapus');
            } else {
                return ApiFormatter::sendResponse(404, 'not found', 'Data category tidak ditemukan');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    
}
