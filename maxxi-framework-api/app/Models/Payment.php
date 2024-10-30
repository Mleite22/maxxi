<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'status',
        'transaction_id',
    ];

    
    public function contract()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
