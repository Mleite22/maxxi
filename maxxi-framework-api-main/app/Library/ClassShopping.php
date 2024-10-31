<?php

namespace App\Library;

use Illuminate\Support\Facades\DB;
use App\Library\ClassContracts;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;


class ClassShopping
{

    private $contract;


    public function __construct($contractId)
    {
        $this->contract = ClassContracts::getContractInfoByID($contractId);
    }


    public function getProducts() { 

        $products = Product::where('status', 'active')
                            ->select('product_id', 'name', 'description', 'price', 'type')
                            ->get();

        return $products;

    }

    public function createInvoice() { 

        $this->cancelInvoice();

        $invoice = Invoice::create([
            "contract_id"  => $this->contract->contract_id,
            "total_amount" => 0,
            "status"       => "pending",
            "due_date"     => now()->addHours(24)
        ]);; 

        return $invoice;
    }

    public function cancelInvoice() { 
        Invoice::where('status', 'pending')->update(['status' => 'cancelled']);
    }

    public function getUserPendingInvoice() { 
        $invoice = Invoice::where('contract_id', $this->contract->contract_id)
                            ->where('status', 'pending')
                            ->with('items')
                            ->with(['items.product'])                           
                            ->first();
        return $invoice;
    }

    public function getPendingInvoicePaymentHistory() { 

        $invoice = $this->getUserPendingInvoice();

        $payments = Payment::select('method', 'transaction_id', 'amount')
        ->where('invoice_id', $invoice->invoice_id)
        ->get();
    
        return $payments;
    }

    public function addItem($productId) { 

        $invoice = $this->getUserPendingInvoice();
        $product = Product::find($productId);

        $item = InvoiceItem::create([
            "invoice_id"   => $invoice->invoice_id,
            "product_id"   => $productId,
            "price"        => $product->price,
        ]);

        Invoice::where('invoice_id', $invoice->invoice_id)->increment('total_amount', $product->price);
        return $item;
    }






}