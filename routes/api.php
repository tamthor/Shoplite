<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
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

// Route::get('/test', function() {
//     return response()->json(['message' => 'API is working']);
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'api', 'prefix' => 'v1'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'destroy'])->middleware('auth:api');
    Route::post('register', [\App\Http\Controllers\Api\AuthenticationController::class, 'savenewUser']);
    Route::post('updateprofile', [\App\Http\Controllers\Api\ApiUserController::class, 'updateProfile'])->middleware('auth:api');
    Route::get('getAllProductList', [ProductController::class, 'getAllProductList'])->middleware('auth:api');
    Route::get('getAllProductCat', [ProductController::class, 'getAllProductCat'])->middleware('auth:api');
    Route::get('getProductCat', [ProductController::class, 'getProductCat'])->middleware('auth:api');

    //Edit User 
    // Route::get('users', [\App\Http\Controllers\Api\ApiEdituserController::class, 'apiuserList']);    
    Route::get('profile', [\App\Http\Controllers\Api\ApiUserController::class, 'viewProfile']);  
    Route::get('profile/edit', [\App\Http\Controllers\Api\ApiUserController::class, 'updateProfile']);  
    Route::get('changepassword', [\App\Http\Controllers\Api\ApiUserController::class, 'updateName']);  
    Route::get('profile', [\App\Http\Controllers\Api\ApiUserController::class, 'viewProfile']);  
    Route::post('upload-photo', [\App\Http\Controllers\Api\ApiUserController::class, 'uploadPhoto']);

    //Product
    Route::get('getAllPets', [\App\Http\Controllers\Api\PetController::class, 'getAllPets']);
    Route::get('pets/{id}', [\App\Http\Controllers\Api\PetController::class, 'getPetDetail']);
    Route::get('categories', [\App\Http\Controllers\Api\PetController::class, 'getAllCategories']);
    Route::post('/orders', [\App\Http\Controllers\Api\OrderDetailController::class, 'store']);

    //Address
    Route::post('addresses', [\App\Http\Controllers\Api\AddressController::class, 'store']);
    // Route::get('/getAddresses', [\App\Http\Controllers\Api\AddressController::class, 'index']);
    Route::get('addresses/{userId}', [\App\Http\Controllers\Api\AddressController::class, 'getUserAddresses']);
    // Cart Routes
    Route::get('cart', [CartController::class, 'index'])->middleware('auth:api'); // Lấy danh sách giỏ hàng
    Route::post('cart/add', [\App\Http\Controllers\Api\CartController::class, 'addToCart'])->middleware('auth:api');
    Route::delete('cart/remove', [CartController::class, 'removeFromCart'])->middleware('auth:api'); 
    // Xóa sản phẩm khỏi giỏ hàng
  });
