<?php

namespace App\Library;


use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Contract;

class ClassContracts{


    public static function updateContractByID($contractId, $data) { 

        Contract::where('contract_id', $contractId)->update($data);

    }


    public static function getContractInfoByID($contractId) { 

        $user = Contract::where('contract_id', $contractId)
        ->with('users')
        ->with('network_info')
        ->first();
        return $user;


    }

    
    public static function getContractInfoByUsername($username) { 

        $user = User::where('username', $username)
        ->with('contract')
        ->with('network_info')
        ->first();

        if($user) { 
            return $user;
        };

        return false;
    }

}