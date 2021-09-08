<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Messages;
use App\Models\Customer;
use App\Models\Staff;
use Validator;

class CustomerController extends BaseController
{
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

        $customer_id_loggedin = Customer::where('user_id', Auth::user()->id)->first();
        if (is_null($customer_id_loggedin)) {
            return $this->sendError('Customer not found.');
        }
        $customer_id_loggedin = $customer_id_loggedin['id'];
        
        $pesan = Messages::create(
            [
                "customer_id"=> $customer_id_loggedin,
                "customer_receiver_id"=> $input['customer_receiver_id'],
                "staff_id"=> null,
                "staff_receiver_id"=> null,
                "messages"=> $input['messages']
            ]
        );
   
        return $this->sendResponse($pesan, 'Messages sent.');
    }

    public function ownChatHistory(Request $request)
    {
        $customer_id_loggedin = Customer::where('user_id', Auth::user()->id)->first();
        if (is_null($customer_id_loggedin)) {
            return $this->sendError('Chat history with your id not found.');
        }
        $customer_id_loggedin = $customer_id_loggedin['id'];
        
        $pesan = Messages::with(array('data_customer_id' => function($q){
            $q->withTrashed()->select('id', 'customer_name');
        }))->with(array('data_customer_receiver_id' => function($q){
            $q->withTrashed()->select('id', 'customer_name');
        }))->with(array('data_staff_id' => function($q){
            $q->select('id', 'staff_name');
        }))->with(array('data_staff_receiver_id' => function($q){
            $q->select('id', 'staff_name');
        }))->where('customer_id', $customer_id_loggedin)->get();
        if (count($pesan) == 0) {
            return $this->sendError('Your chat does not created yet.');
        }
   
        return $this->sendResponse($pesan, 'Your chat history retrieved successfully.');
    }

    public function customerFeedbackOrBug(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'messages' => 'required',
            'staff_receiver_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $customer_id_loggedin = Customer::where('user_id', Auth::user()->id)->first();
        if (is_null($customer_id_loggedin)) {
            return $this->sendError('Customer not found.');
        }
        $customer_id_loggedin = $customer_id_loggedin['id'];
        
        $pesan = Messages::create(
            [
                "customer_id"=> $customer_id_loggedin,
                "customer_receiver_id"=> null,
                "staff_id"=> null,
                "staff_receiver_id"=> $input['staff_receiver_id'],
                "messages"=> $input['messages']
            ]
        );
   
        return $this->sendResponse($pesan, 'Feedback/bug reported.');
    }
}
