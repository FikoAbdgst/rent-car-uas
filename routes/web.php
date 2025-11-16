<?php

use App\Models\Transaksi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PemilikController;
use App\Livewire\LaporanComponent;
use App\Livewire\PenyewaComponent;
use App\Livewire\TransaksiComponent;
use App\Livewire\UnifiedTransaksi;

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'proses'])->name('login.proses');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('login/keluar', [LoginController::class, 'keluar'])->name('login.keluar');


    Route::get('mobil', function () {
        return view('mobil.index');
    })->name('mobil');
    Route::get('transaksi', function () {
        return view('transaksi.index');
    })->name('transaksi');

    Route::get('penyewa', function () {
        return view('penyewa.index');
    })->name('penyewa');

    Route::get('expense', function () {
        return view('expense.index');
    })->name('expense');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {


        // Redirect route lama ke unified transaksi
        Route::redirect('transaksiProses', 'transaksi')->name('transaksiProses');
        Route::redirect('transaksiSelesai', 'transaksi')->name('transaksiSelesai');

        Route::get('sewa', function () {
            return view('sewa.index');
        })->name('sewa');
    });

    // Pemilik only routes
    Route::middleware('role:pemilik')->group(function () {
        Route::get('pemilik/dashboard', [PemilikController::class, 'dashboard'])->name('pemilik.dashboard');
        Route::get('users', function () {
            return view('users.index');
        })->name('users');

        Route::get('laporan', function () {
            return view('laporan.index');
        })->name('laporan');


        Route::get('/log-activity', function () {
            return view('log-activity');
        })->name('log-activity');
    });

    // Admin and Pemilik routes
    Route::middleware('role:admin,pemilik')->group(function () {});
});
