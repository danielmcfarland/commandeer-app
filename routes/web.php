<?php

use App\Http\Controllers\MdmCallbackController;
use App\Http\Controllers\NanomdmController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::domain('{account}.' . config('app.domain'))
    ->get('MDMServiceConfig', function (string $account) {
        return response()->json([
            'dep_anchor_certs_url' => route('mdm.dep_anchor_certs_url', [
                'account' => $account,
            ]),
            'dep_enrollment_url' => route('mdm.callback', [
                'account' => $account,
            ]),
        ]);
    });

Route::domain('{account}.' . config('app.domain'))
    ->prefix('mdm')
    ->name('mdm')
    ->group(function () {
        Route::any('/', MdmCallbackController::class)
            ->name('.callback')
            ->withoutMiddleware([
                ValidateCsrfToken::class,
            ]);

        Route::get('/dep_anchor_certs_url', function () {
            return response()->json();
        })->name('.dep_anchor_certs_url');
    });

Route::prefix('mdm')
    ->group(function () {
        Route::any('/', MdmCallbackController::class)
            ->name('.callback')
            ->withoutMiddleware([
                ValidateCsrfToken::class,
            ]);

        Route::get('/dep_anchor_certs_url', function () {
            return response()->json();
        })->name('.dep_anchor_certs_url');
    });

Route::domain('{account}.' . config('app.domain'))->group(function () {
    Route::any('/{path}', NanomdmController::class)
        ->where('path', '.*');
});
