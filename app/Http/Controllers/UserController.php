<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $users=User::orderBy("id","DESC");
        if($request->get("user_id")){
            $users=$users->where('id',"!=",$request->get("user_id"));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'role' => 'required',
            'username' => 'required|unique:users',
        ]);
       
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }
        $input['password']=Hash::make($input['password']);
        $user = User::create($input);
        return $user;
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
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('Category not found.');
        }
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
           'first_name' => 'required',
           'last_name' => 'required',
           'role' => 'required',
           'username' => 'required|unique:users,username,'.$user->id,
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user->first_name=$input['first_name'];
        $user->last_name=$input['last_name'];
        $user->role=$input['role'];
        $user->username=$input['username'];
        if(isset($input['password'])){
            $user->password=Hash::make($input['password']);
        }
        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        Customer::where("user_id",$user->id)->delete();
        return $user;
    }
}
