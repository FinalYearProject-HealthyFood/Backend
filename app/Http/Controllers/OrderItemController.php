<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderItems = OrderItem::all();

        return response()->json([
            'success' => true,
            'data' => $orderItems
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'meal_id' => 'nullable|integer|exists:meals,id',
            'ingredient_id' => 'nullable|integer|exists:ingredients,id',
        ]);
        $user = Auth::user();
        $meal = null;
        $ingredient = null;

        if ($request['meal_id']) {
            $meal = Meal::find($request['meal_id']);
        } elseif ($request['ingredient_id']) {
            $ingredient = Ingredient::find($request['ingredient_id']);
        } else {
            return response()->json(['error' => 'Either meal or ingredient must be provided.'], 400);
        }

        $orderItem = new OrderItem();
        $orderItem->quantity = $request['quantity'];
        $orderItem->user_id = $user->id;

        if ($meal) {
            $orderItem->meal_id = $meal->id;
            $orderItem->total_price = $meal->price * $orderItem->quantity;
        } elseif ($ingredient) {
            $orderItem->ingredient_id = $ingredient->id;
            $orderItem->total_price = $ingredient->price * $orderItem->quantity;
        }

        $orderItem->save();

        return response()->json($orderItem, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $OrderItem = OrderItem::findOrFail($id);

        return response()->json([
            'data' => $OrderItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);
        $user = Auth::user();

        if ($user->id != $orderItem->user_id) {
            return response()->json(['errors' => 'you dont have this permission'], 422);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $orderItem->quantity = $request['quantity'];
        $meal = null;
        $ingredient = null;

        if ($orderItem->meal_id) {
            $meal = Meal::find($request['meal_id']);
            $orderItem->price = $meal->price * $orderItem->quantity;
        } elseif ($orderItem->ingredient_id) {
            $ingredient = Ingredient::find($request['ingredient_id']);
            $orderItem->price = $ingredient->price * $orderItem->quantity;
        } else {
            return response()->json(['error' => 'Empty order item.'], 400);
        }

        if ($orderItem->save()) {
            return response()->json(['message' => 'Order item updated successfully', 'data' => $orderItem]);
        } else {
            return response()->json(['message' => 'Unable to update order item'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $user = Auth::user();

        if ($user->id != $orderItem->user_id) {
            return response()->json(['errors' => 'you dont have this permission'], 422);
        }
        if ($orderItem->delete()) {
            return response()->json(['message' => 'Order item deleted successfully']);
        } else {
            return response()->json(['message' => 'Unable to delete order item'], 500);
        }
    }
}
