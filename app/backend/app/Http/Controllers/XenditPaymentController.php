<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\Invoice;
use Xendit\Invoice\InvoiceApi;

class XenditPaymentController extends Controller
{
    public function createInvoice(Request $request)
    {
        $apiKey = env('XENDIT_SECRET_KEY');
        $url = "https://api.xendit.co/v2/invoices";

        $data = [
            'external_id' => 'invoice-' . uniqid(),
            'payer_email' => $request->email,
            'description' => 'Test Invoice',
            'amount' => $request->amount,
        ];

        $headers = [
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($apiKey . ":")
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);

        Log::info('Xendit API Response:', ['response' => $responseData]);

        if ($httpCode === 200 || $httpCode === 201) {
            // Store payment details in the database
            Payment::create([
                'external_id' => $responseData['external_id'],
                'email' => $request->email,
                'amount' => $request->amount,
                'status' => $responseData['status'],
                'invoice_url' => $responseData['invoice_url'],
            ]);

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice_url' => $responseData['invoice_url'],
            ]);
        }

        return response()->json([
            'message' => 'Failed to create invoice',
            'error' => $responseData,
        ], 500);
    }


    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['status']) && $data['status'] === 'PAID') {
            // Update payment status in database
            Payment::where('external_id', $data['external_id'])
                ->update(['status' => 'PAID']);
        }

        return response()->json(['message' => 'Webhook received']);
    }

    public function testXenditAPI()
    {
        $apiKey = env('XENDIT_SECRET_KEY');
        $url = "https://api.xendit.co/v2/invoices";

        $data = [
            'external_id' => 'test-123',
            'payer_email' => 'hadrian.fdc@email.com',
            'description' => 'Test Invoice',
            'amount' => 100000,
        ];

        $headers = [
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($apiKey . ":")
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        Log::info('Xendit API Response:', ['response' => json_decode($response, true)]);

        return response()->json(json_decode($response, true));
    }

}
