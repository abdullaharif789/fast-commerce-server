<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Validator;

class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $users=Customer::with("user")->orderBy("id","DESC");
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
        return $users->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'country'=> 'required',
            'service'=> 'required',
            'date'=> 'required',
            'fee'=> 'required',
            'contract_duration'=> 'required',
        ]);
       
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }
        
        $customer = Customer::create($input);
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $customer = Customer::with("user")->find($id);
        if (is_null($customer)) {
            return $this->sendError('Category not found.');
        }
   
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'country'=> 'required',
            'service'=> 'required',
            'date'=> 'required',
            'fee'=> 'required',
            'contract_duration'=> 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $customer->name=$input['name'];
        $customer->country=$input['country'];
        $customer->service=$input['service'];
        $customer->date=$input['date'];
        $customer->fee=$input['fee'];
        $customer->contract_duration=$input['contract_duration'];
        $customer->save();

        return $customer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();
        return $customer;
    }
}
