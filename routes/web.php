<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FinanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('students.data-entry');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::prefix('students')->group(function () {
    Route::get('/', function () {
        return view('students.admission');
    })->name('students.admission');

    Route::get('/admission', function () {
        return view('students.admission');
    })->name('students.admission');

    Route::get('/data-entry', function () {
        return view('students.data-entry');
    })->name('students.data-entry');
});

Route::prefix('finance')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('finance.index');

    Route::get('/create', [FinanceController::class, 'cash'])->name('finance.create');

    Route::get('/payment', [FinanceController::class, 'payment'])->name('finance.payment');
});