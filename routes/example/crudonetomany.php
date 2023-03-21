<?php

use App\Http\Controllers\Example\CrudOneToManyController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::resource('crud-one-to-many', CrudOneToManyController::class)->except([
            'show'
        ]);
    });
