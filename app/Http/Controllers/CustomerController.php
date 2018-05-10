<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json([ 'customers' => $customers], 200);
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'phone_number' => 'required|max:15'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $customer = Customer::create([
           'name' => $request->name,
           'email' => $request->email,
           'phone_number' => $request->phone_number,
        ]);

        return response()->json(
            $customer
        , 200);

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
        $customer = Customer::findOrFail($id);
        return response()->json(
            $customer
        , 200);
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
        $customer = Customer::findOrFail($id);
        if (array_key_exists('name', $request->all())) $customer->name = $request->name;
        if (array_key_exists('email', $request->all())) $customer->email = $request->email;
        if (array_key_exists('location', $request->all())) $customer->location = $request->location;
        if (array_key_exists('phone_number', $request->all())) $customer->phone_number = $request->phone_number;
        if (array_key_exists('lat', $request->all())) $customer->lat = $request->lat;
        if (array_key_exists('lng', $request->all())) $customer->lng = $request->lng;
        $customer->save();
        return response()->json([
            'message' => 'Customer updated successfully'
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
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json([
            'message' => 'Customer deleted successfully'
        ], 200);
    }
}
