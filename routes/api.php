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
Route::post('/utilisateur/inscription', [UserController::class, "inscription"]);
Route::post('/utilisateur/connexion', [UserController::class, "connexion"]);
// Fin
Route::get('api/v1/docs', 'App\Http\Controllers\SwaggerController@index');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reload-wallet', [WalletController::class, 'reload']);
    Route::post('/transfer-money', [WalletController::class, 'transfer']);
    Route::post('/pay', [WalletController::class, 'pay']);
    Route::get('/operations', [OperationsController::class, 'index']);
    Route::post('/claim', [ClaimController::class, 'create']);
    Route::get('/claim', [ClaimController::class, 'index']);
    Route::get('/clients', [AdminController::class, 'getClients']);
});

