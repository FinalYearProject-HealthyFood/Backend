<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meals = Meal::all();

        return response()->json([
            'data' => $meals,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1',
        ]);

        $meal = new Meal();
        $meal->name = $request->name;
        $meal->description = $request->description;
        $meal->price = $request->price;
        $meal->serving_size = $request->serving_size;
        $meal->calories = $request->calories;
        $meal->protein = $request->protein;
        $meal->carb = $request->carb;
        $meal->fat = $request->fat;
        $meal->save();

        if ($request->ingredients) {
            foreach ($request->ingredients as $ingredient) {
                $meal->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
            }
        }

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
        $meal = Meal::with('ingredients')->findOrFail($id);

        return response()->json([
            'data' => $meal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $meal = Meal::findOrFail($id);
        $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1',
        ]);

        if ($request->has('name')) {
            $meal->name = $request->name;
        }
        if ($request->has('description')) {
            $meal->description = $request->description;
        }
        if ($request->has('price')) {
            $meal->price = $request->price;
        }
        if ($request->has('serving_size')) {
            $meal->serving_size = $request->serving_size;
        }
        if ($request->has('calories')) {
            $meal->calories = $request->calories;
        }
        if ($request->has('protein')) {
            $meal->protein = $request->protein;
        }
        if ($request->has('carb')) {
            $meal->carb = $request->carb;
        }
        if ($request->has('fat')) {
            $meal->fat = $request->fat;
        }


        $meal->save();

        if ($request->has('ingredients')) {
            $meal->ingredients()->detach();

            foreach ($request->ingredients as $ingredient) {
                $meal->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
            }
        }

        return response()->json([
            'message' => 'Meal updated successfully',
            'data' => $meal,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);

        $meal->delete();

        return response()->json([
            'message' => 'Meal deleted successfully',
        ]);
    }

    public function destroyAll()
    {
        Meal::truncate();

        return response()->json([
            'message' => 'All meals deleted successfully',
        ]);
    }
}
