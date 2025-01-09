<?php

use App\Http\Controllers\MdmCallbackController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.domain'))->post('mdm', MdmCallbackController::class)
    ->name('mdm-callback')
    ->withoutMiddleware([
        ValidateCsrfToken::class,
    ]);
