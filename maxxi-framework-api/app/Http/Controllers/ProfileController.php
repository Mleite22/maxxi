<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\ClassEmailVerification;
use App\Library\ClassProfile;

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


}
