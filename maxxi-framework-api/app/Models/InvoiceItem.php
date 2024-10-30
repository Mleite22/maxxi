<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';
    protected $primaryKey = 'invoice_item_id';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    
}
