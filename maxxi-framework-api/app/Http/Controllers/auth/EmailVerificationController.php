<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{

    public function requestEmailVerification(Request $request)
    {

        $user = $request->user();

        if($user) { 
            $email = $user->email;
        }else { 
            $email = $request->email;
        }


        $verificationCode = rand(1000, 9999);

        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'verification_code' => $verificationCode,
                'expires_at' => Carbon::now()->addMinutes(30), 
            ]
        );

        Mail::raw("Seu código de verificação é: $verificationCode", function ($message) use ($verificationCode, $email) {
            $message->to($email)
                    ->subject('Verificação de E-mail')
                    ->from('noreply@nextblockchain.global', 'NextBlockchain'); 
        });
        
        return response()->json(['status' => true, 'message' => 'Verification email sent']);
    }


    public function verifyEmailCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'verification_code' => 'required|digits:4'
        ]);

        $emailVerification = EmailVerification::where('email', $validated['email'])
                            ->where('verification_code', $validated['verification_code'])
                            ->first();

        if (!$emailVerification || $emailVerification->isExpired()) {
            return response()->json(['status' => false, 'message' => 'Invalid or expired verification code']);
        }

        $emailVerification->delete();

        return response()->json(['status' => true, 'message' => 'Email verified successfully']);
    }

}
