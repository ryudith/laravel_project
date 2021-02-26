<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LogoutController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\LendController;
use App\Http\Controllers\Backend\PayController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest.custom'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'process']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'process']);
});


Route::middleware(['auth.custom'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/lend', [LendController::class, 'browse'])->name('lend');
    Route::post('/lend/datatables', [LendController::class, 'datatables'])->name('lend.datatables');

    Route::get('/lend/add', [LendController::class, 'add'])->name('lend.add');
    Route::post('/lend/add', [LendController::class, 'processAdd']);

    Route::get('/lend/edit/{lend}', [LendController::class, 'read'])->name('lend.edit');
    Route::post('/lend/edit/{lend}', [LendController::class, 'edit']);

    Route::post('/lend/delete/{lend}', [LendController::class, 'delete'])->name('lend.delete');


    Route::get('/pay', [PayController::class, 'lendBrowse'])->name('pay.lend');
    Route::post('/pay/datatables', [PayController::class, 'lendDatatables'])->name('pay.lend.datatables');

    Route::get('/pay/{lend}', [PayController::class, 'browse'])->name('pay');
    Route::post('/pay/{lend}/datatables', [PayController::class, 'datatables'])->name('pay.datatables');

    Route::get('/pay/{lend}/add', [PayController::class, 'add'])->name('pay.add');
    Route::post('/pay/{lend}/add', [PayController::class, 'processAdd']);

    Route::get('/pay/{lend}/edit/{pay}', [PayController::class, 'read'])->name('pay.edit');
    Route::post('/pay/{lend}/edit/{pay}', [PayController::class, 'edit']);

    Route::post('/pay/delete/{pay}', [PayController::class, 'delete'])->name('pay.delete');


    Route::get('/profile', [ProfileController::class, 'read'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'edit']);

    Route::post('/logout', [LogoutController::class, 'process'])->name('logout');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
