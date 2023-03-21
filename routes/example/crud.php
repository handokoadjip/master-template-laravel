<?php

use App\Http\Controllers\Example\CrudController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::resource('crud', CrudController::class)->except([
            'show'
        ]);
    });
