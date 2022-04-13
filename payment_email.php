<?php
    use App\Models\Customer;

    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
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
