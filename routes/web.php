<?php

use App\Http\Controllers\PaymentController;
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
Route::get('/', function () {
    return view('welcome');
});
// Route::get('/', [PaymentController::class, 'index']);
// Route::post('/transaction', [PaymentController::class, 'makePayment'])->name('make-payment');

Route::get('/stripe', [PaymentController::class, 'stripe']);
Route::post('/payment', [PaymentController::class, 'payStripe'])->name('payment');

Route::get('/coupon', [PaymentController::class, 'coupon']);
Route::post('/coupon', [PaymentController::class, 'createCoupon'])->name('create.coupon');
Route::get('/show', [PaymentController::class, 'show']);