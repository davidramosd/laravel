<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserActiveController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

/* Route::get('/', function () {
    return view('welcome');
})->name('welcome'); */

Route::view('/','welcome')->name('welcome');

Route::get('/dashboard', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('numberid')->group(function () {
    Route::view('/employee', 'employee')->name('employee');
    Route::post('logout_employee', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout_employee');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::controller(UserController::class)->group(function(){
        Route::view('users/data','users.users')->name('users.data');
        Route::get('users-export', 'export')->name('users.export');
        Route::post('users-import', 'import')->name('users.import');
    });

    Route::patch('/users/{user}/active', [UserActiveController::class, 'update'])->name('users.active');
    Route::get('/users/pdf', [UserController::class, 'createPDF'])->name('users.pdf');
    Route::get('/users/clear-filter', [UserController::class, 'clearFilter'])->name('users.clearFilter');
    Route::resource('users', UserController::class, [
        'names' => 'users',
    ]);
    
});

require __DIR__.'/auth.php';
