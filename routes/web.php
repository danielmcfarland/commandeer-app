<?php

use App\Http\Controllers\NanomdmController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::domain('{account}.' . config('app.domain'))->group(function () {
    Route::any('/{path}', NanomdmController::class)
        ->where('path', '.*');
});
