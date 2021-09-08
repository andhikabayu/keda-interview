<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Messages;
use App\Models\Customer;
use App\Models\Staff;
use Validator;

class StaffController extends BaseController
{    

    public function allChatHistory(Request $request)
    {
        $staff_id_loggedin = Staff::where('user_id', Auth::user()->id)->first();
        if (is_null($staff_id_loggedin)) {
            return $this->sendError('Chat history with your id not found.');
        }
        $staff_id_loggedin = $staff_id_loggedin['id'];
        
        $pesan = Messages::with(array('data_customer_id' => function($q){
            $q->withTrashed()->select('id', 'customer_name');
        }))->with(array('data_customer_receiver_id' => function($q){
            $q->withTrashed()->select('id', 'customer_name');
        }))->with(array('data_staff_id' => function($q){
            $q->select('id', 'staff_name');
        }))->with(array('data_staff_receiver_id' => function($q){
            $q->select('id', 'staff_name');
        }))->where('staff_id', $staff_id_loggedin)->orWhere('staff_receiver_id', $staff_id_loggedin)->get();
        if (count($pesan) == 0) {
            return $this->sendError('Your chat does not created yet.');
        }
   
        return $this->sendResponse($pesan, 'Your chat history retrieved successfully.');
    }

    public function allCustomerAndDeletedCustomer(Request $request)
    {   
        $allCustomer = Customer::all();
        $deletedCustomer = Customer::withTrashed()->where('deleted_at', '!=', null)->get();
        
        $data = [
            'customers'=> $allCustomer, 
            'deletedCustomers'=> $deletedCustomer
        ];
   
        return $this->sendResponse($data, 'Customers retrieved successfully.');
    }

    public function messageToAnotherStaff(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'messages' => 'required',
            'staff_receiver_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $staff_id_loggedin = Staff::where('user_id', Auth::user()->id)->first();
        if (is_null($staff_id_loggedin)) {
            return $this->sendError('Staff not found.');
        }
        $staff_id_loggedin = $staff_id_loggedin['id'];
        
        $pesan = Messages::create(
            [
                "customer_id"=> null,
                "customer_receiver_id"=> null,
                "staff_id"=> $staff_id_loggedin,
                "staff_receiver_id"=> $input['staff_receiver_id'],
                "messages"=> $input['messages']
            ]
        );
   
        return $this->sendResponse($pesan, 'Messages sent.');
    }

    public function messageToAnotherCustomer(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'messages' => 'required',
            'customer_receiver_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $staff_id_loggedin = Staff::where('user_id', Auth::user()->id)->first();
        if (is_null($staff_id_loggedin)) {
            return $this->sendError('Staff not found.');
        }
        $staff_id_loggedin = $staff_id_loggedin['id'];
        
        $pesan = Messages::create(
            [
                "customer_id"=> null,
                "customer_receiver_id"=> $input['customer_receiver_id'],
                "staff_id"=> $staff_id_loggedin,
                "staff_receiver_id"=> null,
                "messages"=> $input['messages']
            ]
        );
   
        return $this->sendResponse($pesan, 'Messages sent.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($id)
    {
        Customer::find($id)->delete();
   
        return $this->sendResponse([], 'Customer deleted successfully.');
    }
}
