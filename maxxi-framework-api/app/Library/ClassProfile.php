<?php

namespace App\Library;


use Illuminate\Support\Facades\DB;
use App\Library\ClassEmailVerification;
use App\Models\User;
use App\Models\Contract;

class ClassProfile{

    private $user;

    public function __construct($userId)
    {
        $this->user = User::find($userId);
    }

    public function updateUser($data)
    {
        try {
            $user = User::where('user_id', $this->user->user_id)
                        ->update([
                            'first_name' => $data['firstname'], 
                            'last_name' => $data['lastname'], 
                            'email' => $data['email']
                        ]);
            
            return $user > 0; 
        } catch (\Exception $e) { 
            return false;
        }
    }

    public function updateFinancial($data)
    {
       
        try {
            $contract = Contract::where('contract_id', $this->user->contract_id)
                        ->update([
                            'btc_address' => $data['btcAddress'], 
                            'usdt_address' => $data['usdtAddress'], 
                            'eth_address' => $data['ethAddress']
                        ]);
            
            return $contract > 0; 
        } catch (\Exception $e) { 
            return false;
        }

    }


}