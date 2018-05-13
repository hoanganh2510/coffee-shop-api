<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use Validator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if (array_key_exists('code', $request->all())) {
            $code = $request->query('code');
            $coupon = Coupon::where('code', $code)->first();
            return response()->json($coupon, 200);
        }

        $coupons = Coupon::all();
        return response()->json( [
            'coupons' => $coupons], 200);
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
        //
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'code' => 'required|max:10',
            'percentage' => 'required',
            'status' => 'required',
            'expired_time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $coupon = Coupon::create([
           'description' => $request->description,
            'code' => $request->code,
            'percentage' => $request->percentage,
            'status' => $request->status,
            'expired_time' => $request->expired_time
        ]);

        return response()->json([
            'message' => 'Coupon Created Successfully'
        ], 200);

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
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon, 200);
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
        //
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'code' => 'required|max:10',
            'percentage' => 'required',
            'status' => 'required',
            'expired_time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $coupon = Coupon::findOrFail($id);
        $coupon->description = $request->description;
        $coupon->code = $request->code;
        $coupon->percentage = $request->percentage;
        $coupon->status = $request->status;
        $coupon->expired_time = $request->expired_time;
        $coupon->save();
        return response()->json([
            'message' => 'Customer Updated Successfully'
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
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response()->json([
            'message' => 'Coupon deleted successfully'
        ], 200);
    }
}
