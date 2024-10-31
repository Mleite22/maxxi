<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller; 
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\UserRegistrationService;
use App\Models\EmailVerification;

class RegisterController extends Controller
{

    public function verifyRegisterData(RegisterRequest $request) { 
        return response()->json(['status' => true]);
    }

    public function register(RegisterRequest $request, UserRegistrationService $registerService) { 

        $validatedCode = $request->validate(
            [
                'verification_code' => ['required', 'digits:4'],
            ],
            [
                'verification_code.required' => 'Verification Code is required.',
                'verification_code.digits' => 'Verification Code must be exactly 4 digits.',
            ]
        );

        $emailVerification = EmailVerification::where('email', $request->email)
                                                ->where('verification_code', $request->verification_code)
                                                ->first();

        if (!$emailVerification || $emailVerification->isExpired()) {
            return response()->json(['status' => false, 'message' => 'Invalid or expired verification code.']);
        }

        $this->registerService = $registerService;
        $user = $this->registerService->registerUser($request->all());

        if($user) { 

            $emailVerification->delete();

            return response()->json([
                'status' => true,
                'message' => 'User successfully registered',
            ], 200); 
        } else { 
            return response()->json([
                'status' => false,
                'message' => 'Error',
            ], 400); 
        }
    }

    public function registerFromAdmin(RegisterRequest $request, UserRegistrationService $registerService) { 

        if ($request->api_key == env('api_key')) {
            return response()->json(['status' => false]);
        }

        $this->registerService = $registerService;
        $user = $this->registerService->registerUser($request->all());

        if($user) { 

            return response()->json([
                'status' => true,
                'message' => 'User successfully registered',
            ], 200); 

        } else { 
            return response()->json([
                'status' => false,
                'message' => 'Error',
            ], 400); 
        }
    }


}
