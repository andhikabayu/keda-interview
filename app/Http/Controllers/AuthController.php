<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Customer;
use App\Models\Staff;

class AuthController extends BaseController
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'level' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $userTypeId = '';
        $customerLevel = '';

        if (strtolower($input['level']) == 'customer') {
            $userTypeId = 1;
            $customerLevel = 'customer';
        } else {
            $userTypeId = 2;
            $customerLevel = 'staff';
        }
        
        $user = User::create(
            [
                "user_type_id"=> $userTypeId,
                "email"=> $input['email'],
                "password"=> bcrypt($input['password'])
            ]
        );
        if ($user) {
            if ($customerLevel == 'customer') {
                Customer::create(
                    [
                        "customer_name"=> $input['name'],
                        "user_id"=> $user->id
                    ]
                );
            } else {
                Staff::create(
                    [
                        "staff_name"=> $input['name'],
                        "user_id"=> $user->id
                    ]
                );
            }
        }
        $success['email'] =  $user->email;
   
        return $this->sendResponse($success, 'User register successfully.');
    }

    
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            $success['email'] =  $user->email;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function logout(Request $request)
    {
        $userId = Auth::user()->id;
        $delete = DB::table('oauth_access_tokens')->where('user_id', $userId)->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
