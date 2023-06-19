<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::where('user_id', $user->id)->where('status', 'incart')->get();
        $order = new Order();
        $order->user_id = $user->id;
        $order->delivery_address = $request->delivery_address;
        $order->username = $request->username;
        $order->phone = $request->phone;
        $order->status = "pending";
        $order->total_price = $request->total_price;
        $order->payment_mode = $request->payment_mode;
        $order->payment_id = $request->payment_id;
        $order->save();
        foreach ($orderItems as $orderItem) {
            $orderItem->order_id = $order->id;
            $orderItem->status = "pending";
            $orderItem->save();
            // $order->total_price += $orderItem->total_price;
            $order->save();
        }
        $order->save();
        $order->sendOrderDetailEmail();
        return $order;
    }
}
