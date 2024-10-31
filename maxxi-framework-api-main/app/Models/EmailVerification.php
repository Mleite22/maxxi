<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmailVerification extends Model
{
    protected $fillable = ['email', 'verification_code', 'expires_at'];

    public $timestamps = true;

    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
