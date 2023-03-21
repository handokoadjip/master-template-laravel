<?php

use App\Http\Controllers\Backoffice\WorkUnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::resource('unit-kerja', WorkUnitController::class)->except([
            'show'
        ])->parameters(['unit-kerja' => 'work-unit']);
    });
