<?php

use App\Http\Controllers\Backoffice\ActionController;
use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice/grup')
    ->middleware(['auth', 'prevent-back-history', 'permission'])
    ->group(function () {
        Route::get('aksi/{menuItemId}/{groupId}', [ActionController::class, 'create'])->name('aksi.create');
        Route::post('aksi', [ActionController::class, 'store'])->name('aksi.store');
        Route::get('aksi/{action}/{menuItemId}/{groupId}', [ActionController::class, 'edit'])->name('aksi.edit');
        Route::put('aksi/{action}', [ActionController::class, 'update'])->name('aksi.update');
        Route::delete('aksi/{id}/{groupId}', [ActionController::class, 'destroy'])->name('aksi.destroy');
    });
