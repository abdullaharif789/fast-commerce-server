<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\PaymentResource;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $users=Customer::with("user")->whereRaw("DATEDIFF(NOW() ,`date`) > 27")->orderBy("id","DESC");
            if($request->get('filter')){
            $filter=json_decode($request->get("filter"));
            if(isset($filter->name)){
                $users=$users->where('name','like',"%".strtolower($filter->name)."%");
            }
        }
        if($request->get("user_id")){
            $users=$users->where('user_id',$request->get("user_id"));
        }
        if($request->get("range")){
            $range=json_decode($request->get("range"));
            $users=$users->offset($range[0])->limit($range[1]-$range[0]+1);
        }
        return PaymentResource::collection($users->get());
    }

}