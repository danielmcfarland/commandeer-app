<?php

use App\Http\Controllers\MdmCallbackController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.domain'))
    ->get('MDMServiceConfig', function () {
        return response()->json([
            'dep_anchor_certs_url' => route('mdm.dep_anchor_certs_url'),
            'dep_enrollment_url' => route('mdm.callback'),
        ]);
    });

Route::domain(config('app.domain'))
    ->prefix('mdm')
    ->name('mdm')
    ->group(function () {
        Route::post('/', MdmCallbackController::class)
            ->name('.callback')
            ->withoutMiddleware([
                ValidateCsrfToken::class,
            ]);

        Route::get('/dep_anchor_certs_url', function () {
            return response()->json();
        })->name('.dep_anchor_certs_url');
    });
