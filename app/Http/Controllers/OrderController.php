<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = "";
        if ($request->has('search')) {
            $searchQuery = $request->search;
        }
        $orders = Order::with("user")
            ->when($searchQuery, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->whereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%" . $search . "%")
                            ->orWhere('email', 'like', "%" . $search . "%")
                            ->orWhere('phone', 'like', "%" . $search . "%");
                    })
                        ->orWhere('status', 'like', "%" . $search . "%")
                        ->orWhere('phone', 'like', "%" . $search . "%")
                        ->orWhere('delivery_address', 'like', "%" . $search . "%")
                        ->orWhere('username', 'like', "%" . $search . "%")
                        ->orWhere('id', 'like', "%" . $search . "%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return response()->json($orders);
    }

    public function ordersByUser($id)
    {
        $user = User::findOrFail($id);
        $orders = Order::with("user")->where("user_id", $user->id)->orderBy('id', 'DESC')->paginate(5);

        return response()->json($orders);
    }

    public function showPengdingByUser(Request $request)
    {
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
        $orderItems = OrderItem::where('user_id', $user->id)->where('status', 'incart')->get();
        $order = new Order();
        $order->user_id = $user->id;
        $order->delivery_address = $request->delivery_address;
        $order->username = $request->username;
        $order->phone = $request->phone;
        $order->status = "pending";
        $order->total_price = $request->total_price;
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

    public function sendOrderEmail($id)
    {
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
        $order = Order::where('id', $id)->with("orderItems")->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();
        $orderItems = $order->orderItems;
        foreach ($orderItems as $orderItem) {
            $orderItem->status = $request->status;
            $orderItem->save();
        }
        $order->save();
        $order->sendOrderDetailEmail();
        return response()->json($order);
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
    public function destroyPending($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status != "pending") {
            return response()->json([
                "errors" =>
                [
                    'error' => ['Đơn hàng này trong tình trạng đã được xử lý. Bạn không thể xóa.']
                ]
            ], 422);
        }
        $order->delete();
        return response()->json([
            'message' => 'Order deleted successfully',
        ]);
    }
}
