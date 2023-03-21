<?php

use App\Http\Controllers\Example\CrudOneToOneController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::resource('crud-one-to-one', CrudOneToOneController::class)->except([
            'show'
        ]);
    });
