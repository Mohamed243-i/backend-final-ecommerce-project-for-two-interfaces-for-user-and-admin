<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories=category::all();
       return response()->json($categories);
        } catch (\Throwable $th) {
            return response()->json('somthing is wrong');
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
        $request->validate([
            'name' => 'required|unique:categories,name|max:30|min:4', 
        ]);
        $category=new category();
        $category->name=$request->name;
        $category->save();
        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category=category::findOrFail($id);
       return response()->json($category);
        } catch (\Throwable $th) {
            return response()->json('somthing is wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
           'name' => 'required|max:30|min:4|unique:categories,name,'.$id,
        ]);
        $category= category::find($id);
        $category->name=$request->name;
        $category->save();
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category=category::findOrFail($id);
            $category->delete();
            return response()->json($category);
        } catch (\Throwable $th) {
            return response()->json('error in delete');
        }
       
    }
}
