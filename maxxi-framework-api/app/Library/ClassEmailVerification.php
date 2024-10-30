<?php

namespace App\Library;


use Illuminate\Support\Facades\DB;
use App\Models\EmailVerification;

class ClassEmailVerification{


    public static function verifyEmailCode($email, $code)
    {
        $emailVerification = EmailVerification::where('email', $email)
                            ->where('verification_code', $code)
                            ->first();

        if (!$emailVerification || $emailVerification->isExpired()) {
            return false;
        }

        //$emailVerification->delete();

        return true;
    }

}