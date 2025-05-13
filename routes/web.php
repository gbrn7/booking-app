<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'index'])->name('index');
Route::get('/schedule', [CustomerController::class, 'schedule'])->name('schedule');

Route::get('/getPackagesByClassID', [CustomerController::class, 'getPackagesByClassID'])->name('getPackagesByClassID');
