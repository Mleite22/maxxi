<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\ClassShopping;

class ShoppingController extends Controller
{
    public function getProducts(Request $request) { 

        $user = $request->user();

        $shopping = new ClassShopping($user->contract_id); 
    
        try {

            $products = $shopping->getProducts();
    
            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully.',
                'products' => $products
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createInvoice(Request $request) { 

        $user = $request->user();

        $shopping = new ClassShopping($user->contract_id); 

        try {

            $invoice = $shopping->createInvoice();
            $item    = $shopping->addItem($request->productId);
            return response()->json([
                'status' => true,
                'message' => 'Invoice created successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create invoice.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function cancelInvoice(Request $request) { 

        $user = $request->user();

        $shopping = new ClassShopping($user->contract_id); 

        try {

            $shopping->cancelInvoice();
            return response()->json([
                'status' => true,
                'message' => 'Invoice cancelled successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel invoice.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function getPendingInvoice(Request $request) { 

        $user = $request->user();
        $shopping = new ClassShopping($user->contract_id); 

        $invoice = $shopping->getUserPendingInvoice();

        if($invoice) { 
            return response()->json([
                'status' => true,
                'invoice' => $invoice,
                'message' => 'Has pending invoices.',
            ], 200);
        }else{ 
            return response()->json([
                'status' => false,
                'message' => 'No pending invoices.',
            ], 200);

        }

    }

    public function getPendingInvoicePaymentHistory(Request $request) { 

        $user = $request->user();
        $shopping = new ClassShopping($user->contract_id); 

        $payments = $shopping->getPendingInvoicePaymentHistory();

        if(count($payments) > 0 && $payments) { 
            return response()->json([
                'status' => true,
                'payments' => $payments,
                'message' => 'Has payments.',
            ], 200);
        }else{ 
            return response()->json([
                'status' => false,
                'message' => 'No payments yet.',
            ], 200);

        }

    }

}
