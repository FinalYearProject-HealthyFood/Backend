<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/order-items/store', [OrderItemController::class, 'store'])->name('orderItems.store');
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/test', function () {
    return Auth::user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

// Routes for meals
Route::get('/meals', [MealController::class, 'index'])->name('meals.index');
Route::get('/meals/{id}', [MealController::class, 'show'])->name('meals.show');
Route::post('/meals/store', [MealController::class, 'store'])->name('meals.store');
Route::put('/meals/update/{id}', [MealController::class, 'update'])->name('meals.update');
Route::delete('/meals/delete/{id}', [MealController::class, 'destroy'])->name('meals.destroy');
Route::delete('/meals', [MealController::class, 'destroyAll'])->name('meals.destroyAll');

// Routes for ingredients
Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.index');
Route::get('/ingredients/{id}', [IngredientController::class, 'show'])->name('ingredients.show');
Route::post('/ingredients/store', [IngredientController::class, 'store'])->name('ingredients.store');
Route::put('/ingredients/update/{id}', [IngredientController::class, 'update'])->name('ingredients.update');
Route::delete('/ingredients/delete/{id}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
Route::delete('/ingredients', [IngredientController::class, 'destroyAll'])->name('ingredients.destroyAll');

// Routes for orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
Route::put('/orders/update/{id}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

// Routes for order items
Route::get('/order-items', [OrderItemController::class, 'index'])->name('orderItems.index');
Route::get('/order-items/{id}', [OrderItemController::class, 'show'])->name('orderItems.show');
// Route::post('/order-items/store', [OrderItemController::class, 'store'])->name('orderItems.store');
Route::put('/order-items/update/{id}', [OrderController::class, 'update'])->name('orderItems.update');
Route::delete('/order-items/delete/{id}', [OrderController::class, 'destroy'])->name('orderItems.destroy');