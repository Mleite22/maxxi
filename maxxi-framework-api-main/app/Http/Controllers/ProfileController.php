<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\ClassEmailVerification;
use App\Library\ClassProfile;
use App\Library\ClassTwoFactor;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{

   
    public function updatePersonalData(Request $request) { 

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/'],
            'lastname' => ['required', 'string', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/'],
            'email' => 'required|email|string',
        ]);

        $user = $request->user();

        $emailVerification = ClassEmailVerification::verifyEmailCode($user->email, $request->otp); 

        if(!$emailVerification) { 
            return response()->json([
                'status' => false,
                'message' => "Invalid or expired verification code."
            ], 200);
        }

        $profile = new ClassProfile($user->user_id);
        $update = $profile->updateUser($request);

        if($update) { 
            return response()->json([
                'status' => true,
                'message' => "Your personal info has been updated."
            ], 200);
        }

    }

    public function updateFinancialData(Request $request) { 


        $user = $request->user();

        $emailVerification = ClassEmailVerification::verifyEmailCode($user->email, $request->otp); 

        if(!$emailVerification) { 
            return response()->json([
                'status' => false,
                'message' => "Invalid or expired verification code."
            ], 200);
        }

        $profile = new ClassProfile($user->user_id);
        $update = $profile->updateFinancial($request->data);

        if($update) { 
            return response()->json([
                'status' => true,
                'message' => "Your personal info has been updated."
            ], 200);
        }

    }
 
    public function getTwoFactorNewCredentials(Request $request)
    {
        $user = Auth::user();

        $emailVerification = ClassEmailVerification::verifyEmailCode($user->email, $request->otp); 

        if(!$emailVerification) { 
            return response()->json([
                'status' => false,
                'message' => "Invalid or expired verification code."
            ], 200);
        }

        $twoFactor = new ClassTwoFactor($user->user_id);
        $credentials = $twoFactor->getNewCredentials();

        return response()->json([
            'status' => true, 
            'qr_code_url' => $credentials["qrCodeUrl"], 
            'secret' => $credentials["secret"]
        ]);

    }

    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();

        $twoFactor = new ClassTwoFactor($user->user_id);
        $enable = $twoFactor->enable($request->secret);

        if(!$enable) { 
            return response()->json([
                'status' => false, 
                'message' => "Invalid request."
            ], 200);
        }

        return response()->json([
            'status' => true, 
            'message' => "Two-factor authentication successfully enabled."
        ], 200);
      
    }

    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();

        $emailVerification = ClassEmailVerification::verifyEmailCode($user->email, $request->otp); 

        if(!$emailVerification) { 
            return response()->json([
                'status' => false,
                'message' => "Invalid or expired verification code."
            ], 200);
        }

        $twoFactor = new ClassTwoFactor($user->user_id);
        $disable = $twoFactor->disable();

        if(!$disable ) { 
            return response()->json([
                'status' => false, 
                'message' => "Invalid request."
            ], 200);
        }

        return response()->json([
            'status' => true, 
            'message' => "Two-factor authentication successfully disabled."
        ], 200);

    }


}
