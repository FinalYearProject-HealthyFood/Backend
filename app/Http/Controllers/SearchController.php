<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function searchHome(Request $request)
    {
        $searchQuery = "";
        if ($request->has('search')) {
            $searchQuery = $request->search;
        }
        $data = Ingredient::select('id', 'name', 'serving_size', 'price', 'calories', 'image', DB::raw("'ingredient' as category"), 'ingredients.name')
            ->where('status', 'active')
            ->where('name', 'like', '%' . $searchQuery . '%')
            ->unionAll(
                Meal::select('id', 'name', 'serving_size', 'price', 'calories', 'image', DB::raw("'meal' as category"), 'meals.name')
                    ->where('status', 'active')
                    ->where('name', 'like', '%' . $searchQuery . '%')
            )
            ->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
