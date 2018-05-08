<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductDetail;
use Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::with('productDetails')->get();
        return response()->json([
           'products' => $products
        ], 200);
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
            'name' => 'required',
            'image_url' => 'required',
            'description' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $product = Product::create([
           'name' => $request->name,
            'image_url' => $request->image_url,
            'description' => $request->description,
            'category_id' => $request->category_id
        ]);


        foreach ($request->product_details as $product_detail) {
            $detail = ProductDetail::create([
               'size' => $product_detail['size'],
               'price' => $product_detail['price'],
               'product_id' => $product->id
            ]);
        }

        return response()->json([
            'message' => 'Product Created'
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
        $product = Product::find($id);

        $product->name = $request->name;
        $product->image_url = $request->image_url;
        $product->description = $request->description;
        $product->save();

        foreach ($request->product_details as $product_detail) {
            if ($product_detail['id'] != null) {
                $detail = ProductDetail::find($product_detail['id']);
                $detail->size = $product_detail['size'];
                $detail->price = $product_detail['price'];
                $detail->save();
            } else {
                $detail = ProductDetail::create([
                    'size' => $product_detail['size'],
                    'price' => $product_detail['price'],
                    'product_id' => $product->id
                ]);
            }

        }

        foreach ($request->remove_product_details as $remove_detail) {
            $detail = ProductDetail::find($remove_detail);
            $detail->delete();
        }

        return response()->json([
            'message' => 'Delete Product Successfully'
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
        $product = Product::find($id);
        $product->delete();
        return response()->json([
            'message' => 'Delete successfully'
        ], 200);
    }
}
