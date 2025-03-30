<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DocumentRequestController;
//         ];
//
//         // Validate the request data
//         $validatedData = $request->validate($rules);
//
//         // --- Store the request ---

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/requests/create', [DocumentRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [DocumentRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests', [DocumentRequestController::class, 'index'])->name('requests.index'); // List user's requests
    Route::get('/requests/{documentRequest}', [DocumentRequestController::class, 'show'])->name('requests.show'); // View details of a specific request
    Route::get('/requests/{documentRequest}/pay', [DocumentRequestController::class, 'pay'])->name('requests.pay'); // Initiate payment
});

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleCallback'])->name('google.callback');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Add other admin routes here later
});
