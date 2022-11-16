<?php

use App\Constants\StatusCodes;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthenticationController::class, 'login']);
//Route::post('register', [ApiController::class, 'register']);

Route::fallback(function () {
    dd('sdsd');
});

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('auth/refresh', [AuthenticationController::class, 'refresh']);
    Route::get('auth/me', [AuthenticationController::class, 'me']);
    // Route::get('logout', [ApiController::class, 'logout']);
    // Route::get('get_user', [ApiController::class, 'get_user']);
    // Route::get('products', [ProductController::class, 'index']);
    // Route::get('products/{id}', [ProductController::class, 'show']);
    // Route::post('create', [ProductController::class, 'store']);
    // Route::put('update/{product}',  [ProductController::class, 'update']);
    // Route::delete('delete/{product}',  [ProductController::class, 'destroy']);


    /**
    * Fall Back any undefined API Request
    *
    *
    */
    Route::fallback(function (Request $request) {
        return response()->json(['message' => 'Requested Resource Not Found!', 'success'=> false], StatusCodes::HTTP_NOT_FOUND);
    });

});
