<?php

use App\Http\Controllers\Backoffice\GroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::get('grup/hak-akses/{group}', [GroupController::class, 'permissionCreate'])->name('grup.permissionCreate');
        Route::post('grup/hak-akses/{group}', [GroupController::class, 'permissionSync'])->name('grup.permissionSync');
        Route::resource('grup', GroupController::class)->except([
            'show'
        ])->parameters(['grup' => 'group']);
    });
