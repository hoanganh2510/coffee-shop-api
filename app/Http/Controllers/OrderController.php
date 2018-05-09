<?php

namespace App\Http\Controllers;

use App\ProductDetail;
use Illuminate\Http\Request;
use Validator;
use App\Order;
use App\OrderItem;
use App\Coupon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $order = new Order;

        if (array_key_exists('coupon_code', $request->all())) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            $order->coupon_id = $coupon->id;
        }
        $order->status = $request->status;
        $order->shipping_fee = $request->shipping_fee;
        $order->customer_id = $request->customer_id;
        $order->shop_id = $request->shop_id;
        $order->save();

        foreach ($request->order_items as $order_item) {
            $product_detail = ProductDetail::findOrFail($order_item['product_detail_id']);
            $item = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $order_item['product_id'],
                'price' => $product_detail->price,
                'size' => $product_detail->size,
                'quantity' => $order_item['quantity']
            ]);
        }

        return response()->json([
            'message' => 'Order Created'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $order = Order::findOrFail($id);
        if (array_key_exists('coupon_code', $request->all())) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            $order->coupon_id = $coupon->id;
        }
        $order->status = $request->status;
        $order->save();

        if (array_key_exists('order_items', $request->all())) {
            foreach ($request->order_items as $order_item) {
                if (array_key_exists('id', $order_item)) {
                    $item = OrderItem::findOrFail($order_item['id']);
                    $product_detail = ProductDetail::findOrFail($order_item['product_detail_id']);
                    $item->size = $product_detail->size;
                    $item->price = $product_detail->price;
                    $item->quantity = $order_item['quantity'];
                    $item->save();
                } else {
                    $product_detail = ProductDetail::findOrFail($order_item['product_detail_id']);
                    $item = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $order_item['product_id'],
                        'price' => $product_detail->price,
                        'size' => $product_detail->size,
                        'quantity' => $order_item['quantity']
                    ]);
                }
            }
        }

        if (array_key_exists('remove_order_items', $request->all())) {
            foreach ($request->remove_order_items as $remove_item) {
                $item = OrderItem::findOrFail($remove_item);
                $item->delete();
            }
        }

        return response()->json([
            'message' => 'Updated Successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            'message' => 'Order Deleted Successfully'
        ], 200);
    }
}
