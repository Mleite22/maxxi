<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use DB;

class PasswordRecoverController extends Controller
{

 
    public function request(Request $request) { 


        $user = User::where('username', $request->username)->first();


        if($user) { 
            $this->sendRecoverEmail($user);
        }

        return response()->json([
            'status'      => true,
            'message'     => "If an account with this email exists, we've sent a message with instructions to reset your password."
        ]);       
    }

    public function recover(Request $request) { 

        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')
                     ->where('token', $request->token)
                     ->first();
        
        
        if (!$tokenRecord || Carbon::parse($tokenRecord->created_at)->addMinutes(30)->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token.'
            ]);
        }

        DB::table('users')->where('username', $tokenRecord->username)->update([
            'password' => Hash::make($request->password),
        ]);
    
        DB::table('password_reset_tokens')->where('token', $request->token)->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Password has been reset successfully!'
        ]);
    }

    private function sendRecoverEmail($user)
    {

        $username = $user->username;
        $email = $user->email;
        $token = bin2hex(random_bytes(32));

        $resetLink = env('OFFICE_URL') . '/password-recover/' . $token;

        DB::table('password_reset_tokens')->updateOrInsert(
            ['username' => $user->username], 
            [
                'username' => $username, 
                'email' => $email, 
                'token' => $token, 
                'created_at' => Carbon::now() 
            ]
        );
      
        Mail::send('emails.password-recover', ['resetLink' => $resetLink], function ($message) use ($email) {
            $message->to($email)
                ->subject('Password Reset Request')
                ->from('noreply@nextblockchain.global', 'NextBlockchain'); 
        });
        
    }

}
