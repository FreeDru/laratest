<?php

use App\Http\Controllers\Api\V1\Auth\LoginTelegramController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\client\clientInfoController;
use App\Http\Controllers\Api\V1\Order\OrderController;
use App\Http\Controllers\Api\V1\parser\parserController;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api\V1\Auth')->prefix('api/v1')->middleware('json.api')->group(function () {
    Route::post('/login/telegram', LoginTelegramController::class);
    Route::post('/login/telegram/checklogin', [LoginTelegramController::class, 'checkLogin']);
    Route::post('/login/telegram/activation', [LoginTelegramController::class, 'activation']);
    Route::post('/logout', 'LogoutController')->middleware('auth:api');
});

Route::namespace('Api\V1\client')
    ->prefix('api/v1/client')
    ->middleware('json.verif.api')->group(function () {
        Route::post('/info', [clientInfoController::class, 'index']);
        Route::post('/update', [clientInfoController::class, 'update']);
        Route::post('/update/all', [clientInfoController::class, 'updateAll']);
    });

Route::post('register', RegisterController::class)->name('register');

Route::namespace('Api\V1\parser')->prefix('api/v1/parser')->middleware('json.api')->group(function () {
    Route::get('/getinfo', [parserController::class, 'parseInfo']);
});

Route::middleware([
//    'json.verif.api',
    'json.api'
])->group(function () {
    Route::post('orders', [OrderController::class, 'store'])->name('store.order');
    Route::get('orders/{order}', [OrderController::class, 'view'])->name('view.order');
    Route::get('orders', [OrderController::class, 'list'])->name('list.orders');
    Route::delete('orders/{order}', [OrderController::class, 'delete'])->name('delete.order');
});

