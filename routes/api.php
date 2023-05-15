<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\VerifyEmailController;
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
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/test', function () {
    return Auth::user();
});

Route::post('/login', [AuthController::class, 'login']);
// Route::post('/login', [AuthController::class, 'login'])->middleware(['verified']);
Route::post('/signup', [AuthController::class, 'signup']);

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', [VerifyEmailController::class, 'resend'])
    ->middleware(['auth:sanctum'])
    ->name('verification.send');

// Routes for meals
Route::controller(MealController::class)->group(function () {
    Route::group(['prefix' => 'meals'], function () {
        Route::get('/', [MealController::class, 'index'])->name('meals.index');
        Route::get('/{id}', [MealController::class, 'show'])->name('meals.show');
        Route::post('/store', [MealController::class, 'store'])->name('meals.store');
        Route::put('/update/{id}', [MealController::class, 'update'])->name('meals.update');
        Route::delete('/delete/{id}', [MealController::class, 'destroy'])->name('meals.destroy');
        Route::delete('/', [MealController::class, 'destroyAll'])->name('meals.destroyAll');
    });
});

// Routes for ingredients
Route::controller(IngredientController::class)->group(function () {
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('/', [IngredientController::class, 'index'])->name('ingredients.index');
        Route::get('/{id}', [IngredientController::class, 'show'])->name('ingredients.show');
        Route::post('/store', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::put('/update/{id}', [IngredientController::class, 'update'])->name('ingredients.update');
        Route::delete('/delete/{id}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
        Route::delete('/', [IngredientController::class, 'destroyAll'])->name('ingredients.destroyAll');
    });
});

// Routes for orders
Route::controller(OrderController::class)->group(function () {
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/update/{id}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
        });
    });
});

// Routes for order items
Route::controller(OrderItemController::class)->group(function () {
    Route::group(['prefix' => 'order-items'], function () {
        Route::get('/', [OrderItemController::class, 'index'])->name('orderItems.index');
        Route::get('/{id}', [OrderItemController::class, 'show'])->name('orderItems.show');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/store', [OrderItemController::class, 'store'])->name('orderItems.store');
            Route::put('/update/{id}', [OrderItemController::class, 'update'])->name('orderItems.update');
            Route::delete('/delete/{id}', [OrderItemController::class, 'destroy'])->name('orderItems.destroy');
            Route::delete('/delete-all-by-user', [OrderItemController::class, 'destroy_all_by_user'])->name('orderItems.destroy_all_by_user');
        });
    });
});
