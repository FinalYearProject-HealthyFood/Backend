<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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
    Route::post('/profile/update', [UserController::class, 'update']);
    Route::post('/profile/change-password', [UserController::class, 'changePassword']);
});

Route::get('/test', function () {
    return Auth::user();
});

Route::post('/login', [AuthController::class, 'login']);
// Route::post('/login', [AuthController::class, 'login'])->middleware(['verified']);
Route::post('/signup', [AuthController::class, 'signup']);

// Verify email
// Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyEmail'])
//     ->middleware(['signed'])
//     ->name('verification.verify');
Route::get('/verify-email/{id}/{token}', [VerifyEmailController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', [VerifyEmailController::class, 'resend'])
    ->middleware(['auth:sanctum'])
    ->name('verification.send');

// Routes for meals
Route::controller(MealController::class)->group(function () {
    Route::group(['prefix' => 'meals'], function () {
        Route::get('/', [MealController::class, 'index'])->name('meals.index');
        Route::get('/all', [MealController::class, 'all'])->name('meals.all');
        Route::get('/high-rating', [MealController::class, 'getHighestRating'])->name('meals.getHighestRating');
        Route::get('/{id}', [MealController::class, 'show'])->name('meals.show');
        Route::get('/get-high-star-list/{id}', [MealController::class, 'getListHighRatingByUser'])->name('meals.getListHighRatingByUser');
        Route::post('/store', [MealController::class, 'store'])->name('meals.store');
        Route::post('/fromai', [MealController::class, 'dietFromAi'])->name('meals.dietFromAi');
        Route::post('/save-image/{id}', [MealController::class, 'saveImage'])->name('meals.saveImage');
        Route::put('/update/{id}', [MealController::class, 'update'])->name('meals.update');
        Route::delete('/delete/{id}', [MealController::class, 'destroy'])->name('meals.destroy');
        Route::delete('/', [MealController::class, 'destroyAll'])->name('meals.destroyAll');
    });
});

Route::controller(UserController::class)->group(function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/today-eaten-calories-by-user/{id}', [UserController::class, 'getTodayCaloriesEatenByUser'])->name('users.getTodayCaloriesEatenByUser');
        Route::post('/update-by-manager', [UserController::class, 'updateByManager']);
        Route::post('/store', [UserController::class, 'store']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });
});

// Routes for ingredients
Route::controller(IngredientController::class)->group(function () {
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('/', [IngredientController::class, 'index'])->name('ingredients.index');
        Route::get('/all', [IngredientController::class, 'all'])->name('ingredients.all');
        Route::get('/high-rating', [IngredientController::class, 'getHighestRating'])->name('ingredients.getHighestRating');
        Route::get('/all-filter', [IngredientController::class, 'allToFilter'])->name('ingredients.allToFilter');
        Route::get('/all-filter-active', [IngredientController::class, 'allToFilterActive'])->name('ingredients.allToFilterActive');
        Route::get('/datatoai', [IngredientController::class, 'DataToAI'])->name('ingredients.DataToAI');
        Route::get('/get-high-star-list/{id}', [IngredientController::class, 'getListHighRatingByUser'])->name('ingredients.getListHighRatingByUser');
        Route::get('/get-low-star-list/{id}', [IngredientController::class, 'getListLowRatingByUser'])->name('ingredients.getListLowRatingByUser');
        Route::get('/{id}', [IngredientController::class, 'show'])->name('ingredients.show');
        Route::post('/store', [IngredientController::class, 'store'])->name('ingredients.store');
        Route::post('/save-image/{id}', [IngredientController::class, 'saveImage'])->name('ingredients.saveImage');
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
        Route::get('/by-user/{id}', [OrderController::class, 'ordersByUser'])->name('orders.ordersByUser');
        Route::get('/send-email/{id}', [OrderController::class, 'sendOrderEmail'])->name('orders.sendOrderEmail');
        Route::put('/update/{id}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::middleware('auth:sanctum', 'role:user,admin,ordermod,foodmod,manager')->group(function () {
            Route::post('/store', [OrderController::class, 'store'])->middleware(['verified'])->name('orders.store');
        });
    });
});

// Routes for order items
Route::controller(OrderItemController::class)->group(function () {
    Route::group(['prefix' => 'order-items'], function () {
        Route::get('/', [OrderItemController::class, 'index'])->name('orderItems.index');
        Route::get('/{id}', [OrderItemController::class, 'show'])->name('orderItems.show');
        Route::delete('/delete/{id}', [OrderItemController::class, 'destroy'])->name('orderItems.destroy');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/store', [OrderItemController::class, 'store'])->name('orderItems.store');
            Route::post('/fromai', [OrderItemController::class, 'dietFromAi'])->name('orderItems.dietFromAi');
            Route::put('/update/{id}', [OrderItemController::class, 'update'])->name('orderItems.update');
            Route::delete('/delete-by-user/{id}', [OrderItemController::class, 'destroyByUser'])->name('orderItems.destroy');
            Route::delete('/delete-all-by-user', [OrderItemController::class, 'destroy_all_by_user'])->name('orderItems.destroy_all_by_user');
            Route::delete('/delete-all-incart-by-user', [OrderItemController::class, 'destroy_all_pending_by_user'])->name('orderItems.destroy_all_pending_by_user');
            Route::post('/on-pending-by-user', [OrderItemController::class, 'onPendingByUser'])->name('orderItems.onPendingByUser');
            Route::post('/delivery-last-2-days-by-user', [OrderItemController::class, 'deliverylast2DaysByUser'])->name('orderItems.deliverylast2DaysByUser');
            Route::post('/delivery-today-by-user', [OrderItemController::class, 'deliveryInDayByUser'])->name('orderItems.deliveryInDayByUser');
            Route::post('/on-cart-by-user', [OrderItemController::class, 'onCartByUser'])->name('orderItems.onCartByUser');
            Route::post('/cart-count-by-user', [OrderItemController::class, 'cartCountByUser'])->name('orderItems.onPendingByUser');
            Route::post('/item-deliverd-by-user', [OrderItemController::class, 'itemDeliverdByUser'])->name('orderItems.onPendingByUser');
        });
    });
});

Route::controller(RatingController::class)->group(function () {
    Route::group(['prefix' => 'rate'], function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/meal/{id}', [RatingController::class, 'rateMeal'])->name('rate.rateMeal');
            Route::get('/ingredient/{id}', [RatingController::class, 'rateIngredient'])->name('rate.rateIngredient');
        });
    });
});
Route::controller(PaypalController::class)->group(function () {
    Route::group(['prefix' => 'paypal'], function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/store', [PaypalController::class, 'store'])->name('orderItems.store');
        });
    });
});
Route::controller(RoleController::class)->group(function () {
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('rate.index');
    });
});

Route::controller(SearchController::class)->group(function () {
    Route::group(['prefix' => 'search'], function () {
        Route::get('/home', [SearchController::class, 'searchHome'])->name('search.searchHome');
    });
});

Route::controller(FaqController::class)->group(function () {
    Route::group(['prefix' => 'faq'], function () {
        Route::get('/', [FaqController::class, 'index'])->name('faq.index');
        Route::get('/all', [FaqController::class, 'all'])->name('faq.all');
        Route::get('/{id}', [FaqController::class, 'show'])->name('faq.show');
        Route::post('/', [FaqController::class, 'store'])->name('faq.store');
        Route::put('/{id}', [FaqController::class, 'update'])->name('faq.update');
        Route::delete('/{id}', [FaqController::class, 'destroy'])->name('faq.delete');
    });
});
