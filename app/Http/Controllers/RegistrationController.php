<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Http\Resources\RegistrationResource;
use App\Models\User;
use Validator;

class RegistrationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->get("user_id")){
            return [];
        }
        else{
            $user_id=$request->get("user_id");
            $currentUser=User::find($user_id);
            if($currentUser->role == "admin"){
                $registrations=Registration::orderBy("id","DESC");
            }
            else{
                $registrations=Registration::where('region',$currentUser->region)->orderBy("id","DESC");
            }
            $count=$registrations->get()->count();
            if($request->get('filter')){
                $filter=json_decode($request->get("filter"));
                if(isset($filter->region)){
                    $users=$registrations->where('region',strtolower($filter->region));
                }
                $count=$registrations->get()->count();
            }
            if($request->get("sort")){
                $sort=json_decode($request->get("sort"));
                $registrations = $registrations->orderBy($sort[0],$sort[1]);
            }
            if($request->get("range")){
                $range=json_decode($request->get("range"));
                $registrations=$registrations->offset($range[0])->limit($range[1]-$range[0]+1);
            }
            return $this->sendResponse(RegistrationResource::collection($registrations->get()),$count);
        }
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
            'first_name' => 'required',
            'last_name'=> 'required',
            'email'=> 'required|unique:registrations',
            'contact'=> 'required',
            'region'=> 'required',
            'course'=> 'required',
            'fee'=> 'required',
            'batch'=> 'required',
            'transaction_id'=> 'required',
            'national_identity'=> 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $registration = Registration::create($input);
        return $registration;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = Registration::find($id);
        if (is_null($registration)) {
            return $this->sendError('Registration not found.');
        }

        return $registration;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registration $registration)
    {
        //
        $registration->delete();
        return $registration;
    }
}
