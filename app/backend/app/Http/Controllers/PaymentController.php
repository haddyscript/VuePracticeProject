<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function createInvoice()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('XENDIT_SECRET_KEY') . ':'),
            'Content-Type' => 'application/json'
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => 'invoice-12345',
            'amount' => 1000, // Amount in PHP
            'payer_email' => 'customer@email.com',
            'description' => 'Payment for Order #12345',
            'success_redirect_url' => 'http://localhost:3000/thankyou',
            'failure_redirect_url' => 'http://localhost:3000/checkout'
        ]);

        $invoice = $response->json();

        if (isset($invoice['invoice_url'])) {
            return redirect($invoice['invoice_url']); 
        }

        return response()->json(['error' => 'Failed to create invoice'], 500);
    }
    public function handlePayment(Request $request)
    {
        return response()->json(['message' => 'Xendit payment received'], 200);
    }
}
