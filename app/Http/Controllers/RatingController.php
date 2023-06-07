<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function rateMeal(Request $request, $id)
    {
        try {
            //code...
            $meal = Meal::findOrFail($id);
            $meal_id = $meal->id;
            if ($meal->is_rating_by_auth($request->user_id)) {
                $rate_meal = Rating::where('meal_id', $meal_id)->where('user_id', $request->user_id)->first();
                $rate_meal->rating = $request->rating;
                $rate_meal->save();
                $rating = $meal->rating()->sum('rate');
                $num_rating = $meal->rating()->count();
                $mealrate = (int)$rating / $num_rating;
                $meal->rating = $mealrate;
                $meal->save();
                return response()->json($rate_meal);
            } else {
                $rate_meal = new Rating();
                $rate_meal->meal_id = $meal_id;
                $rate_meal->user_id = $request->user_id;
                $rate_meal->rating = $request->rating;
                $rate_meal->save();
                $rating = $meal->rating()->sum('rate');
                $num_rating = $meal->rating()->count();
                $mealrate = $rating / $num_rating;
                $meal->rating = $mealrate;
                $meal->save();
                return response()->json($rate_meal);
            }
            return response()->json(["error" => "Please, correctly!"], 500);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["error" => "Please, fill in correctly!"], 500);
        }
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
