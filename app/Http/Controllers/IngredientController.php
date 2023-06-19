<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::withCount("rating")->where('status', 'active')->paginate(6);

        return response()->json($ingredients);
    }

    public function all(Request $request)
    {
        $ingredients = Ingredient::where('name', 'like', '%' . $request->search . '%')->orderBy('id', 'DESC')->paginate(5);

        return response()->json($ingredients);
    }

    public function allToFilter()
    {
        $ingredients = Ingredient::all();

        return response()->json($ingredients);
    }

    public function allToFilterActive()
    {
        $ingredients = Ingredient::where('status', 'active')->get();

        return response()->json($ingredients);
    }

    public function DataToAI()
    {
        $ingredients = Ingredient::where('status', 'active')->get();

        return response()->json($ingredients);
    }

    public function getHighestRating()
    {
        $ingredients = Ingredient::where('status', 'active')
            ->orderBy('rate', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(4)
            ->get();
        return response()->json($ingredients);
    }
    public function getListHighRatingByUser($id)
    {
        $user = User::findOrFail($id);
        $LikedIngredients = $user->ingredient_rating()
            ->whereIn('rate', [3, 4, 5])
            ->with('ingredient')
            ->orderBy('rate', 'desc')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get()
            ->pluck('ingredient');
        $DislikedIngredients = $user->ingredient_rating()
            ->whereIn('rate', [1, 2])
            ->with('ingredient')
            ->orderBy('rate', 'asc')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get()
            ->pluck('ingredient');
        return response()->json([
            'likedlist' => $LikedIngredients,
            'dislikedlist' => $DislikedIngredients
        ]);
    }

    public function getListLowRatingByUser($id)
    {
        $user = User::findOrFail($id);
        $LikedIngredients = $user->ingredient_rating()
            ->whereIn('rate', [3, 4, 5])
            ->with('ingredient')
            ->orderBy('rate', 'desc')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get()
            ->pluck('ingredient');
        $DislikedIngredients = $user->ingredient_rating()
            ->whereIn('rate', [1, 2])
            ->with('ingredient')
            ->orderBy('rate', 'asc')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get()
            ->pluck('ingredient');
        return response()->json([
            'likedlist' => $LikedIngredients,
            'dislikedlist' => $DislikedIngredients
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
        if ($request->has('sat_fat')) {
            $ingredient->sat_fat = $request->sat_fat;
        }
        if ($request->has('trans_fat')) {
            $ingredient->trans_fat = $request->trans_fat;
        }
        if ($request->has('fiber')) {
            $ingredient->fiber = $request->fiber;
        }
        if ($request->has('sugar')) {
            $ingredient->sugar = $request->sugar;
        }
        if ($request->has('cholesterol')) {
            $ingredient->cholesterol = $request->cholesterol;
        }
        if ($request->has('sodium')) {
            $ingredient->sodium = $request->sodium;
        }
        if ($request->has('calcium')) {
            $ingredient->calcium = $request->calcium;
        }
        if ($request->has('iron')) {
            $ingredient->iron = $request->iron;
        }
        if ($request->has('zinc')) {
            $ingredient->zinc = $request->zinc;
        }
        if ($request->has('status')) {
            $ingredient->status = $request->status;
        }
        $ingredient->save();

        $image = $request->file('image');
        if ($image) {
            $type = $image->getClientOriginalExtension();

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                return response()->json([
                    'message' => 'Invalid image type',
                ], 422);
            }

            $dir = 'images/ingredients';
            $file = Str::random() . '.' . $type;
            if ($ingredient->image) {
                $exitspath = 'public/' . $ingredient->image;
                Storage::delete($exitspath);
            }
            $ingredient->image = $request->file('image')->storeAs($dir, $file, 'public');
            $ingredient->save();
            return $ingredient;
        }

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
        $ingredient = Ingredient::with('rating')->findOrFail($id);

        return response()->json($ingredient);
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
        if ($request->has('sat_fat')) {
            $ingredient->sat_fat = $request->sat_fat;
        }
        if ($request->has('trans_fat')) {
            $ingredient->trans_fat = $request->trans_fat;
        }
        if ($request->has('fiber')) {
            $ingredient->fiber = $request->fiber;
        }
        if ($request->has('sugar')) {
            $ingredient->sugar = $request->sugar;
        }
        if ($request->has('cholesterol')) {
            $ingredient->cholesterol = $request->cholesterol;
        }
        if ($request->has('sodium')) {
            $ingredient->sodium = $request->sodium;
        }
        if ($request->has('calcium')) {
            $ingredient->calcium = $request->calcium;
        }
        if ($request->has('iron')) {
            $ingredient->iron = $request->iron;
        }
        if ($request->has('zinc')) {
            $ingredient->zinc = $request->zinc;
        }
        if ($request->has('status')) {
            $ingredient->status = $request->status;
        }

        $ingredient->save();

        return response()->json([
            'message' => 'Ingredient updated successfully',
            'data' => $ingredient,
        ]);
    }

    public function saveImage(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $image = $request->file('image');
        if ($image) {
            $type = $image->getClientOriginalExtension();

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                return response()->json([
                    'message' => 'Invalid image type',
                ], 422);
            }

            // $dir = 'public\images\meals\\';
            $dir = 'images/ingredients';
            $file = Str::random() . '.' . $type;
            // if (!Storage::exists($dir)) {
            //     Storage::makeDirectory($dir);
            // }
            if ($ingredient->image) {
                $exitspath = 'public/' . $ingredient->image;
                Storage::delete($exitspath);
            }
            $ingredient->image = $request->file('image')->storeAs($dir, $file, 'public');
            $ingredient->save();
            return $ingredient;
        } else {
            return response()->json([
                'message' => 'Image not found',
            ], 500);
        }
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
