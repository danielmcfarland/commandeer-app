<?php

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.domain'))->post('mdm', function (Request $request) {
    //
})->name('mdm-callback')->withoutMiddleware([
    ValidateCsrfToken::class,
]);
