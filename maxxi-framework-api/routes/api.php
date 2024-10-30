<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\EmailVerificationController;
use App\Http\Controllers\auth\PasswordRecoverController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\ProfileController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout']);

    /*** Network ***/

    Route::post('/network/get-network', [NetworkController::class, 'getNetworkTree']);
    Route::post('/network/get-network-node', [NetworkController::class, 'getNetworkNode']);

    /*** Shopping ***/

    Route::get('/shopping/get-products', [ShoppingController::class, 'getProducts']);
    Route::post('/shopping/create-invoice', [ShoppingController::class, 'createInvoice']);
    Route::post('/shopping/cancel-invoice', [ShoppingController::class, 'cancelInvoice']);
    Route::post('/shopping/get-pending-invoices', [ShoppingController::class, 'getPendingInvoice']);
    Route::post('/shopping/get-pending-invoice-payment-history', [ShoppingController::class, 'getPendingInvoicePaymentHistory']);


    /*** Profile ***/

    Route::post('/profile/personal-data', [ProfileController::class, 'updatePersonalData']);
    Route::post('/profile/financial-data', [ProfileController::class, 'updateFinancialData']);

    /*** Email code verification for logged users ***/
   Route::post('/user/request-email-verification', [EmailVerificationController::class, 'requestEmailVerification']);


});


Route::post('login', [LoginController::class, 'login']);

/*** Password recover ***/

Route::post('/password-recover/request', [PasswordRecoverController::class, 'request']);
Route::post('/password-recover/recover', [PasswordRecoverController::class, 'recover']);

/*** Register ***/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/register-by-admin', [RegisterController::class, 'registerFromAdmin']);
Route::post('/register/verify-register-data', [RegisterController::class, 'verifyRegisterData']);

/*** Email code verification ***/

Route::post('/verify-email-code', [EmailVerificationController::class, 'verifyEmailCode']);
Route::post('/request-email-verification', [EmailVerificationController::class, 'requestEmailVerification']);
