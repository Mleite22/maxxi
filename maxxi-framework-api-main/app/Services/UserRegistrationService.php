<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Contract;
use App\Models\NetworkInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Library\ClassContracts;
use App\Library\ClassNetwork;
use Illuminate\Support\Facades\Mail;

class UserRegistrationService
{
    public function registerUser($data)
    {

        DB::beginTransaction();

        try{ 

            $sponsor = ClassContracts::getContractInfoByUsername($data["sponsor"]);

            $contract = Contract::create([
                'contract_sponsor_id'       => $sponsor->contract->contract_id,
                'contract_parent_id'        => $sponsor->contract->contract_id,
                'network_side'              => 'H',
                'status'                    => 'I',
            ]);

            $networkInfo = NetworkInfo::create([ 
                'contract_id'               => $contract->contract_id,
            ]);

            $user = User::create([
                'username'                      => $data['username'],
                'contract_id'                   => $contract->contract_id,
                'email'                         => $data['email'],
                'first_name'                    => $data['firstname'],
                'last_name'                     => $data['lastname'],
                'password'                      => Hash::make($data['password']),
            ]);

            $network = new ClassNetwork($contract->contract_id);
            $network->addToNetwork();


            DB::commit();


        }catch(e){ 

            DB::rollBack();

            return [
                'status' => false,
                'error' => $e->getMessage()
            ];


        }
        //$this->sendWelcomeEmail($data);
        return true;
    }

    protected function sendWelcomeEmail($user)
    {

        $username = $user["username"];
        $email = $user["email"];

        Mail::raw("Welcome $username", function ($message) use ($username, $email) {
            $message->to($email)
                    ->subject('Welcome')
                    ->from('noreply@nextblockchain.global', 'NextBlockchain');
        });
    }

    protected function notifyAdmin($user)
    {
        // Logica para alertar o patrocionador direto ou admin.
    }
}
