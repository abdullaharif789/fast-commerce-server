<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
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
        $users=Customer::with("user");
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
        return $this->sendResponse(CustomerResource::collection($users->get()),$count);
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
            'company'=> 'required',
            'advance'=> 'required|numeric|min:0',
            'fee'=> 'required|numeric|min:0',
            'sharing'=> 'required|numeric|min:0|max:100',
            'contract_duration'=> 'required|numeric|min:0',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $new_document_path = null;
        // Copy Document
        if($request->document){
            $document = $request->document;
            list($type, $document) = explode(';', $document);
            list(, $document) = explode(',', $document);
            $document = base64_decode($document);
            $type=explode("/",$type)[1];
            $new_document_path=uniqid().".".$type;
            file_put_contents('storage/documents/'.$new_document_path, $document);
        }
        $input['document']=$new_document_path;
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

        return new CustomerResource($customer);
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
            'company'=> 'required',
            'advance'=> 'required|numeric|min:0',
            'fee'=> 'required|numeric|min:0',
            'sharing'=> 'required|numeric|min:0|max:100',
            'contract_duration'=> 'required',
            'payment_verified'=> 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customer->name=$input['name'];
        $customer->country=$input['country'];
        $customer->service=$input['service'];
        $customer->date=$input['date'];
        $customer->fee=$input['fee'];
        $customer->advance=$input['advance'];
        $customer->company=$input['company'];
        $customer->sharing=$input['sharing'];
        $customer->contract_duration=$input['contract_duration'];
        $customer->payment_verified = isset($input['payment_verified']) && $input['payment_verified'] == "Yes" ? true:false;
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
