<?php

namespace App\Http\Controllers;

use App\ProductDetail;
use Illuminate\Http\Request;
use Validator;
use App\Order;
use App\OrderItem;
use App\Coupon;
use App\Shop;

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
        $orders = Order::with('orderItems', 'orderItems.product')->get();
        return response()->json([
            'orders' => $orders], 200);
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

    public function transaction($id) {
        $order = Order::findOrFail($id);
        $total_price = 0;
        foreach ($order->orderItems as $order_item) {
            $total_price = $total_price + $order_item->price * $order_item->quantity;
        }

        $total_price = $total_price + $order->shipping_fee;

        if ($order->coupon_id != null) {
            $coupon = Coupon::findOrFail($order->coupon_id);
            $total_price = $total_price - $total_price * $coupon->percentage / 100;
        }


        return response()->json(['price' => $total_price], 200);
    }

    public function getDistance(Request $request) {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $shops = Shop::all();
        $distances = [];
        foreach ($shops as $shop) {
            $dis = $this->distance((float) $lat, (float) $lng, $shop->lat, $shop->lng, "K");
            $ret_val = array('shop_id' => $shop->id, 'distance' => $dis, 'fee' => $this->calculate_fee($dis));
            array_push($distances, $ret_val);
        }
        return response()->json(['distances' => $distances], 200);
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        
        if ($unit == "K") {
            return ($miles * 1.609344 * 1000);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    private function calculate_fee($distance) {
        if ($distance < 3000) {
            return 0;
        } else {
            return round($distance * 5, -3);
        }
    }
}
