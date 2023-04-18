<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::all();

        return response()->json([
            'data' => $ingredients,
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
        ]);

        $ingredient = new Ingredient();
        $ingredient->name = $request->name;
        $ingredient->serving_size = $request->serving_size;
        $ingredient->price = $request->price;
        $ingredient->calories = $request->calories;
        $ingredient->protein = $request->protein;
        $ingredient->carb = $request->carb;
        $ingredient->fat = $request->fat;
        $ingredient->save();

        return response()->json([
            'message' => 'Tạo thành phần ăn thành công!',
            'data' => $ingredient,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ingredient = Ingredient::findOrFail($id);

        return response()->json([
            'data' => $ingredient,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
        ]);

        if ($request->has('name')) {
            $ingredient->name = $request->name;
        }
        if ($request->has('price')) {
            $ingredient->price = $request->price;
        }
        if ($request->has('serving_size')) {
            $ingredient->serving_size = $request->serving_size;
        }
        if ($request->has('calories')) {
            $ingredient->calories = $request->calories;
        }
        if ($request->has('protein')) {
            $ingredient->protein = $request->protein;
        }
        if ($request->has('carb')) {
            $ingredient->carb = $request->carb;
        }
        if ($request->has('fat')) {
            $ingredient->fat = $request->fat;
        }

        $ingredient->save();

        return response()->json([
            'message' => 'Ingredient updated successfully',
            'data' => $ingredient,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $ingredient->delete();

        return response()->json([
            'message' => 'Ingredient deleted successfully',
        ]);
    }

    public function destroyAll()
    {
        Ingredient::truncate();

        return response()->json([
            'message' => 'All Ingredients deleted successfully',
        ]);
    }
}
