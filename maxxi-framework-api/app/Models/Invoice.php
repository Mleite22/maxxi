<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'contract_id',
        'total_amount',
        'status',
        'due_date',
        'paid_at',
    ];

    
    public function items()
    {
        return $this->hasMany(invoiceItem::class, 'invoice_id', 'invoice_id');
    }
}
