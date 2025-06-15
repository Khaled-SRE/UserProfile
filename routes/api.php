<?php

// use App\Http\Controllers\API\v1\UserProfileController;

use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserProfileImageController;
use Illuminate\Support\Facades\Route;

// Route::prefix('v1')->group(function () {
//     Route::get('user-profile/{userId}', [UserProfileController::class, 'show']);
//     Route::post('user-profile/{userId}', [UserProfileController::class, 'store']);
// });

Route::prefix('user-profile')->name('user-profile.')->group(function () {
    Route::post('/{userId}', [UserProfileController::class, 'store'])->name('store');
    Route::get('/{userId}', [UserProfileController::class, 'show'])->name('show');
    Route::put('/{userId}', [UserProfileController::class, 'update'])->name('update');
    Route::delete('/{userId}', [UserProfileController::class, 'destroy'])->name('destroy');
    Route::post('/{userId}/image', [UserProfileImageController::class, 'store'])->name('image.store');
    Route::delete('/{userId}/image', [UserProfileImageController::class, 'destroy'])->name('image.destroy');
});
