<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function (){

    Route::group(['prefix'=>'book', 'as'=>'books.'], function (){
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
        Route::get('{book:slug}', [BookController::class, 'show'])->withoutMiddleware('auth')->name('show');
    });

    Route::group(['prefix'=>'user' , 'as'=>'user.'],function (){
        Route::get('books', [BookController::class, 'index'])->name('books.list');
        Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('books/{book:slug}', [BookController::class, 'update'])->name('books.update');
        Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });

});

Route::group(['middleware'=>'isAdmin', 'prefix'=>'admin', 'as'=>'admin.'], function(){
    Route::get('', AdminDashboardController::class)->name('index');
    Route::resource('books', AdminBookController::class);
    Route::put('books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('users', AdminUsersController::class);

});

require __DIR__ . '/auth.php';
