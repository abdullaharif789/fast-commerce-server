<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\PaymentResource;
use Carbon\Carbon;

class PaymentController extends BaseController
{
    private function getPaymentCustomers(){
        $currentDay = Carbon::now()->day;
        $customers = Customer::with("user")->where("payment_verified",false)->whereRaw("day(`date`) - ".$currentDay." <= 3");
        return $customers;
    }
    public function index(Request $request)
    {
        $users=$this->getPaymentCustomers();
        $count=$users->get()->count();
        if($request->get('filter')){
            $filter=json_decode($request->get("filter"));
            if(isset($filter->name)){
                $users=$users->where('name','like',"%".strtolower($filter->name)."%");
            }
            $count=$users->get()->count();
        }
        if($request->get("user_id")){
            $users=$users->where('user_id',$request->get("user_id"));
            $count=$users->get()->count();
        }
        if($request->get("sort")){
            $sort=json_decode($request->get("sort"));
            $users = $users->orderBy($sort[0],$sort[1]);
        }
        if($request->get("range")){
            $range=json_decode($request->get("range"));
            $users=$users->offset($range[0])->limit($range[1]-$range[0]+1);
        }
        return $this->sendResponse(PaymentResource::collection($users->get()),$count);
    }
    public function email(){
        $from = "admin@fcportal.com";
        // $to = "ahmadkhan_03@yahoo.com";
        $to = "abdullaharif789@gmail.com";
        $subject = "Payment Trigger ".date("d-M-Y");
        $customers=$this->getPaymentCustomers()->get();
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
    }

}
