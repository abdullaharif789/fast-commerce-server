<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

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

use App\Http\Controllers\PaymentController;

Route::post('/login', 'App\Http\Controllers\ApiAuthController@login')->name('login.api');
Route::apiResource('users', 'App\Http\Controllers\UserController');
Route::apiResource('customers', 'App\Http\Controllers\CustomerController');
Route::apiResource('registrations', 'App\Http\Controllers\RegistrationController');
Route::apiResource('payments', 'App\Http\Controllers\PaymentController');
Route::get('/payment_email', [PaymentController::class, 'email']);
