<?php

use App\Http\Controllers\Backoffice\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::get('pengguna/lihat/{user}', [UserController::class, 'show'])->name('pengguna.show');
        Route::resource('pengguna', UserController::class)->except([
            'show'
        ])->parameters(['pengguna' => 'user']);
    });
