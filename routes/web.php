<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect('/admin'),
            'account' => redirect('/account'),
            'client' => redirect('/client'),
            default => redirect('/'),
        };
    }

    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
