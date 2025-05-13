<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'index'])->name('index');
Route::get('/schedule', [CustomerController::class, 'schedule'])->name('schedule');

Route::get('/getPackagesByClassTypeID', [CustomerController::class, 'getPackagesByClassTypeID'])->name('getPackagesByClassTypeID');


Route::post('/book-class', [CustomerController::class, 'bookClass'])->name('book.class');

Route::get('/book-class/success', function () {
  return view('success-page');
})->name('book.class.success');

Route::put('/redeem-book-code', [CustomerController::class, 'redeemBookCode'])->name('redeem.book.code');

Route::post('api/webhook', [CustomerController::class, 'handlerWebhook']);
