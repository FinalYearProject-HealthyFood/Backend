<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class  OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderItems = OrderItem::with("meal")->with("ingredient")->get();

        return response()->json($orderItems);
    }

    public function onPendingByUser(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::with('meal')
            ->with('ingredient')
            ->where("status", "pending")
            ->where("user_id", $user->id)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($orderItems);
    }

    public function deliverylast2DaysByUser(Request $request)
    {
        $startDate = Carbon::now()->subDays(2)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $user = Auth::user();
        $orderItems = OrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->with('meal')->with('ingredient')
            ->where("status", "delivered")
            ->where("for_me", "yes")
            ->where("user_id", $user->id)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($orderItems);
    }
    public function deliveryInDayByUser(Request $request)
    {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $user = Auth::user();
        $orderItems = OrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->with('meal')->with('ingredient')
            ->where("status", "delivered")
            ->where("for_me", "yes")
            ->where("user_id", $user->id)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($orderItems);
    }

    public function onCartByUser(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::with('meal')->with('ingredient')->where("status", "incart")->where("user_id", $user->id)->orderBy('id', 'DESC')->get();

        return response()->json($orderItems);
    }

    public function cartCountByUser(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::where("status", "incart")->where("user_id", $user->id)->get();

        return response()->json($orderItems->count());
    }

    public function cartTotalPriceByUser(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::where("status", "pending")->where("user_id", $user->id)->sum('total_price');

        return response()->json($orderItems);
    }

    public function itemDeliverdByUser(Request $request)
    {
        $user = Auth::user();
        $orderItems = OrderItem::with('meal')->with('ingredient')->where("status", "delivered")->where("user_id", $user->id)->orderBy('id', 'DESC')->paginate(5);

        return response()->json($orderItems);
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

    public function dietFromAi(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required',
        ]);
        $user = Auth::user();
        $meal = new Meal();
        $meal->name = $request->name;
        $meal->description = $request->description;
        $meal->price = $request->price;
        $meal->serving_size = $request->serving_size;
        $meal->calories = $request->calories;
        $meal->protein = $request->protein;
        $meal->carb = $request->carb;
        $meal->fat = $request->fat;
        if ($request->has('sat_fat')) {
            $meal->sat_fat = $request->sat_fat;
        }
        if ($request->has('trans_fat')) {
            $meal->trans_fat = $request->trans_fat;
        }
        if ($request->has('fiber')) {
            $meal->fiber = $request->fiber;
        }
        if ($request->has('sugar')) {
            $meal->sugar = $request->sugar;
        }
        if ($request->has('cholesterol')) {
            $meal->cholesterol = $request->cholesterol;
        }
        if ($request->has('sodium')) {
            $meal->sodium = $request->sodium;
        }
        if ($request->has('calcium')) {
            $meal->calcium = $request->calcium;
        }
        if ($request->has('iron')) {
            $meal->iron = $request->iron;
        }
        if ($request->has('zinc')) {
            $meal->zinc = $request->zinc;
        }
        $meal->status = "deactive";
        $meal->user_id = $user->id;
        $meal->save();

        if ($request->ingredients) {
            foreach ($request->ingredients as $ingredient) {
                $meal->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
            }
        }

        $orderItem = new OrderItem();
        $orderItem->quantity = 1;
        $orderItem->user_id = $user->id;

        $orderItem->meal_id = $meal->id;
        $orderItem->total_price = $meal->price * $orderItem->quantity;
        if ($request->has('for_me')) {
            $orderItem->for_me = $request->for_me;
        }

        $orderItem->save();

        return response()->json([
            'message' => 'Meal created successfully',
            'data' => $meal,
        ]);
    }
    public function dietSelfCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        $user = Auth::user();
        $orderItems = OrderItem::with('meal')->with('ingredient')->where("status", "incart")->where("user_id", $user->id)->whereNotNull("ingredient_id")->orderBy('id', 'DESC')->get();
        if (count($orderItems) == 0) {
            return response()->json([
                "errors" =>
                [
                    'error' => ['Không có thành phần được chọn nào']
                ]
            ], 422);
        }
        $meal = new Meal();
        $meal->name = $request->name;
        if ($request->has('description')) {
            $meal->description = $request->description;
        }
        $meal->price = 0;
        $meal->serving_size = 0;
        $meal->calories = 0;
        $meal->protein = 0;
        $meal->carb = 0;
        $meal->fat = 0;
        $meal->sat_fat = 0;
        $meal->trans_fat = 0;
        $meal->fiber = 0;
        $meal->sugar = 0;
        $meal->cholesterol = 0;
        $meal->sodium = 0;
        $meal->calcium = 0;
        $meal->iron = 0;
        $meal->zinc = 0;
        $meal->status = "deactive";
        $meal->user_id = $user->id;
        $meal->save();
        foreach ($orderItems as $orderItem) {
            $meal->price += $orderItem->total_price;
            $meal->serving_size += ($orderItem->ingredient->serving_size * $orderItem->quantity);
            $meal->calories += ($orderItem->ingredient->calories * $orderItem->quantity);
            $meal->protein += ($orderItem->ingredient->protein * $orderItem->quantity);
            $meal->carb += ($orderItem->ingredient->carb * $orderItem->quantity);
            $meal->fat += ($orderItem->ingredient->fat * $orderItem->quantity);
            $meal->sat_fat += ($orderItem->ingredient->sat_fat * $orderItem->quantity);
            $meal->trans_fat += ($orderItem->ingredient->trans_fat * $orderItem->quantity);
            $meal->fiber += ($orderItem->ingredient->fiber * $orderItem->quantity);
            $meal->sugar += ($orderItem->ingredient->sugar * $orderItem->quantity);
            $meal->cholesterol += ($orderItem->ingredient->cholesterol * $orderItem->quantity);
            $meal->sodium += ($orderItem->ingredient->sodium * $orderItem->quantity);
            $meal->calcium += ($orderItem->ingredient->calcium * $orderItem->quantity);
            $meal->iron += ($orderItem->ingredient->iron * $orderItem->quantity);
            $meal->zinc += ($orderItem->ingredient->zinc * $orderItem->quantity);
            $meal->save();
            $meal->ingredients()->attach($orderItem->ingredient->id, ['quantity' => $orderItem->quantity]);
        }
        $orderItemNew = new OrderItem();
        $orderItemNew->quantity = 1;
        $orderItemNew->user_id = $user->id;
        $orderItemNew->meal_id = $meal->id;
        $orderItemNew->total_price = $meal->price * $orderItemNew->quantity;
        if ($request->has('for_me')) {
            $orderItemNew->for_me = $request->for_me;
        }
        $orderItemNew->save();

        return response()->json([
            'message' => 'Meal created successfully',
            'data' => $meal,
        ]);
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
            $meal = Meal::find($orderItem->meal_id);
            $orderItem->total_price = $meal->price * $orderItem->quantity;
        } elseif ($orderItem->ingredient_id) {
            $ingredient = Ingredient::find($orderItem->ingredient_id);
            $orderItem->total_price = $ingredient->price * $orderItem->quantity;
        } else {
            return response()->json(['error' => 'Empty order item.'], 400);
        }

        if ($orderItem->save()) {
            return response()->json(['message' => 'Order item updated successfully', 'data' => $orderItem]);
        } else {
            return response()->json(['message' => 'Unable to update order item'], 500);
        }
    }

    public function updateForme(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $user = Auth::user();

        if ($user->id != $orderItem->user_id) {
            return response()->json(['errors' => 'you dont have this permission'], 422);
        }

        $orderItem->for_me = $request['for_me'];

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

        if ($orderItem->delete()) {
            return response()->json(['message' => 'Order item deleted successfully']);
        } else {
            return response()->json(['errors' => 'Unable to delete order item'], 500);
        }
    }

    public function destroyByUser($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $user = Auth::user();

        if ($user->id != $orderItem->user_id) {
            return response()->json(['errors' => 'you dont have this permission'], 422);
        }
        if ($orderItem->delete()) {
            return response()->json(['message' => 'Order item deleted successfully']);
        } else {
            return response()->json(['errors' => 'Unable to delete order item'], 500);
        }
    }

    public function destroy_all_by_user()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['errors' => 'you dont have this permission'], 422);
        }
        OrderItem::where('user_id', $user->id)->where("status", "pending")->delete();
        return response()->json(['message' => 'Order items deleted successfully']);
    }

    public function destroy_all_pending_by_user()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['errors' => 'you dont have this permission'], 422);
        }
        OrderItem::where('user_id', $user->id)->where("status", "incart")->delete();
        return response()->json(['message' => 'Order items deleted successfully']);
    }
}
