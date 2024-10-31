<?php

namespace App\Library;


use Illuminate\Support\Facades\DB;
use App\Library\ClassEmailVerification;
use App\Models\User;
use App\Models\Contract;
use PragmaRX\Google2FA\Google2FA;

class ClassTwoFactor{

    private $user;

    public function __construct($userId)
    {
        $this->user = User::find($userId);
    }

    public function getNewCredentials()
    {

        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();
        $this->user->two_factor_secret = $secret;
        $this->user->save();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            env('APP_NAME'),            
            $this->user->email,          
            $secret                
        );

        $return = [ 
            "qrCodeUrl" => $qrCodeUrl,
            "secret"    => $secret
        ];

        return $return;
    }

    public function enable($secret)
    {

        if($this->user->two_factor_secret != $secret) { 
            return false;
        }

        $google2fa = new Google2FA();
        $this->user->two_factor_enabled = true;
        $this->user->save();

        return true;
    }

    public function disable()
    {

        $google2fa = new Google2FA();
        $this->user->two_factor_enabled = false;
        $this->user->save();
        
        return true;
    }

    public function verify($totp) { 

        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($this->user->two_factor_secret, $totp);
        return $isValid;

    }


}