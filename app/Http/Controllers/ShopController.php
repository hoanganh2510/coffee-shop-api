<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $shops = Shop::all();
        return response()->json([
          'shops' =>  $shops], 200);
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
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'lat' => 'required',
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $shop = Shop::create([
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);

        return response()->json([
            'message' => 'Shop created'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $shop = Shop::findOrFail($id);
        return response()->json([
            $shop
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'lat' => 'required',
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $shop = Shop::find($id);
        $shop->address = $request->address;
        $shop->lat = $request->lat;
        $shop->lng = $request->lng;
        $shop->save();

        return response()->json([
            'message' => 'Shop updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $shop = Shop::findOrFail($id);
        $shop->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ], 200);
    }
}
