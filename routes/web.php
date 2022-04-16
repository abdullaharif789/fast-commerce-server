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

Route::get('/', function () {
    return view('welcome');
});


Route::post('/login', 'App\Http\Controllers\ApiAuthController@login')->name('login.api');
Route::apiResource('users', 'App\Http\Controllers\UserController');
Route::apiResource('customers', 'App\Http\Controllers\CustomerController');
Route::apiResource('registrations', 'App\Http\Controllers\RegistrationController');
Route::apiResource('payments', 'App\Http\Controllers\PaymentController');
Route::get('/payment_email', function(){
    $currentDay = Carbon::now()->day;
    $from = "admin@fcportal.com";
    // $to = "ahmadkhan_03@yahoo.com";
    $to = "affanrasheed07@gmail.com";
    $subject = "Payment Trigger ".date("d-M-Y");
    $customers=Customer::with("user")->where("payment_verified",false)->whereRaw("day(`date`) - ".$currentDay." <= 3")->get();
    foreach($customers as $customer){
        if($customer->payment_verified==false){
            $message = "Dear ".$customer->name.",<br>
            Your payment is not verified. Please verify your payment.<br>
            Thank you.";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: $from";
            if(mail($to,$subject,$message,$headers)){
                echo "email sent to ".$customer->name."<br>";
            }
            else{
                echo "email not sent to ".$customer->name."<br>";
            }
        }
    }
});
