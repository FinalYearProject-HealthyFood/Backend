<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function showPengdingByUser(Request $request) {
        $user = Auth::user();
        $orderItems = OrderItem::where('user_id', $user->id)->get();

        return response()->json($orderItems);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::where('user_id', $user->id)->where('status', 'pending')->get();
        $order = new Order();
        $order->user_id = $user->id;
        $order->delivery_address = $request->delivery_address;
        $order->username = $request->username;
        $order->phone = $request->phone;
        $order->save();
        foreach ($orderItems as $orderItem) {
            $orderItem->order_id = $order->id;
            $orderItem->status = "accepted"; 
            $orderItem->save();
            $order->total_price += $orderItem->total_price;
            $order->save();
        }
        $order->sendOrderDetailEmail();
        return $order;
    }

    public function sendOrderEmail($id){
        $order = Order::find($id);
        $order->sendOrderDetailEmail();
        // try {
        //     //code...
        //     $order->sendOrderDetailEmail();
        //     $isSent = true;
        // } catch (\Exception $e) {
        //     //throw $th;
        //     $isSent = false;
        // }
        // if($isSent) {
        //     return response()->json(["data" => "success"], 200);
        // }
        // else {
        //     return response()->json(["data" => "fail"], 200);
        // }
        return response()->json(['data' => $order], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            'message' => 'Order deleted successfully',
        ]);
    }
}
