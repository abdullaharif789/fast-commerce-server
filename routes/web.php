<?php

use App\Models\Customer;
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


Route::post('/login', 'App\Http\Controllers\ApiAuthController@login')->name('login.api');
Route::apiResource('users', 'App\Http\Controllers\UserController');
Route::apiResource('customers', 'App\Http\Controllers\CustomerController');
Route::apiResource('registrations', 'App\Http\Controllers\RegistrationController');
Route::apiResource('payments', 'App\Http\Controllers\PaymentController');
Route::get('/payment_email', function(){
    $from = "admin@fcportal.com";
    $to = "abdullaharif789@gmail.com";
    $subject = "Payment Trigger";
    $message = "Test Name : ".Customer::find(1)->name;
    $headers = "From:" . $from;
    if(mail($to,$subject,$message, $headers)) {
        echo "The email message was sent.";
    } else {
        echo "The email message was not sent.";
    }

});
