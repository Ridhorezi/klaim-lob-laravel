<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KlaimController;

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/klaim', [KlaimController::class, 'index'])->name('klaim.index');
    Route::post('/klaim/import', [KlaimController::class, 'import'])->name('klaim.import');
    Route::post('/klaim/sendApi', [KlaimController::class, 'sendApi'])->name('klaim.send');

});

require __DIR__ . '/auth.php';
