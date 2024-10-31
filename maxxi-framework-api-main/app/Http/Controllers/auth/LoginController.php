<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Library\ClassTwoFactor;
use DB;

class LoginController extends Controller
{

    public function login(Request $request) { 

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The provided credentials are incorrect.',
            ], 403); 
        }

        if($user->two_factor_enabled && !$request->totp) { 
            return response()->json([
                'status' => true,
                'two_factor_enabled' => true,
                'message' => 'Two-factor authentication required',
            ], 200); 
        } 

        if($user->two_factor_enabled) { 
            $twoFactor = new ClassTwoFactor($user->user_id);
            $verify = $twoFactor->verify($request->totp);

            if(!$verify) { 
                return response()->json([
                    'status' => false,
                    'message' => 'The provided credentials are incorrect.',
                ], 403); 
            }
        }

        $userData = $this->getUserData($user->user_id);
        $token = $user->createToken($request->password)->plainTextToken;

        return response()->json([
            'status'    => true,
            'user'      => $userData,
            'token'     => $token
        ]);

    }

    public function logout(Request $request) { 

        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ], 200);

    } 

    public function getUserData($userId) {
        
        $user = DB::select('
            select 
                u.username, u.first_name, u.last_name, u.email, u.two_factor_enabled,
                c.btc_address, c.usdt_address, c.eth_address
            from users u
            inner join contracts c on u.contract_id = c.contract_id
            where u.user_id = :userId
        ', ['userId' => $userId]);
    

        return $user[0];


    }

}
