<?php

use App\Http\Controllers\WalletController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SwaggerController;
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
// Début Inscription et Connexion
Route::prefix('user')->group(function () {
    Route::post('/inscription', [UserController::class, "inscription"]);
    Route::post('/connexion', [UserController::class, "connexion"]); 
});
// Fin
Route::get('api/v1/docs', 'App\Http\Controllers\SwaggerController@index');


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('wallet')->group(function () {
        Route::post('/reload', [WalletController::class, 'reload']);
        Route::post('/transfer', [WalletController::class, 'transfer']);
        Route::post('/pay', [WalletController::class, 'pay']);
        Route::post('/associate-card', [WalletController::class, 'associateCard']);
        Route::post('/recharge-card', [WalletController::class, 'rechargeCard']);
        Route::post('/pay-with-card', [WalletController::class, 'payWithCard']);
        Route::post('/recharge-mobile-money', [WalletController::class, 'rechargeMobileMoney']);

    });

    Route::get('/operations', [OperationsController::class, 'index']);

    Route::prefix('user')->group(function () {
        Route::post('/claims', [ClaimController::class, 'create']);
        Route::get('/claims', [ClaimController::class, 'index']);
    });
    Route::prefix('admin')->group(function (){
        Route::get('/claims/{claimId}', [ClaimController::class, 'show']);
        Route::get('/clients', [AdminController::class, 'getClients']);
        Route::get('/claims/{claimId}', [AdminController::class, 'handleClaim']);
        Route::get('/claims', [AdminController::class, 'index']);

    });

    
});

