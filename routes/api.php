<?php

use App\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('sms')->group(function () {
        Route::post('/send', [SMSController::class, 'send']);
        Route::get('/messages', [SMSController::class, 'list']);
        Route::get('/pending', [SMSController::class, 'pending']);
        Route::get('/messages/{id}', [SMSController::class, 'view']);
        Route::put('/messages/{id}/cancel', [SMSController::class, 'cancel']);
    });
});
