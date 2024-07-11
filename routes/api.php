<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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


/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
|
|
*/

// Authentication routes
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
Route::get('me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');


// User routes
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::middleware('auth:api')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
