<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    // auth routing
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    // Route::get('userList','AuthController@getUserList');
});

Route::middleware('auth:api')->group( function () {
    // customer routing
    Route::post('customer/message-to-other-customer', [CustomerController::class, 'messageToAnotherCustomer'])->name('customer.messageToAnotherCustomer');
    Route::get('customer/own-chat-history', [CustomerController::class, 'ownChatHistory'])->name('customer.ownChatHistory');
    Route::post('customer/customer-feedback-or-bug', [CustomerController::class, 'customerFeedbackOrBug'])->name('customer.customerFeedbackOrBug');

    // staff routing
    Route::get('staff/all-chat-history', [StaffController::class, 'allChatHistory']);
    Route::get('staff/all-customer-and-deleted-customer', [StaffController::class, 'allCustomerAndDeletedCustomer']);
    Route::post('staff/message-to-other-staff', [StaffController::class, 'messageToAnotherStaff']);
    Route::post('staff/message-to-other-customer', [StaffController::class, 'messageToAnotherCustomer']);
    Route::post('staff/delete-customer/{id}', [StaffController::class, 'deleteCustomer']);

    // logout
    Route::post('logout', [AuthController::class, 'logout']);
});