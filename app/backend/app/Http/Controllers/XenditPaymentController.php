<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentEwallet;
use Xendit\Configuration;
use Xendit\Invoice\Invoice;
use Xendit\Invoice\InvoiceApi;

class XenditPaymentController extends Controller
{
    public function createInvoice(Request $request)
    {
        $apiKey = env('XENDIT_SECRET_KEY');
        $url = env('XENDIT_URL');

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

    public function createEwalletPayment(Request $request)
    {
        $apiKey = env('XENDIT_SECRET_KEY');
        $url = env('XENDIT_E_WALLET_CHARGE');

        $externalId = 'ewallet-' . uniqid();
        $allowedChannels = ['GCASH', 'SHOPEEPAY', 'DANA', 'OVO', 'LINKAJA', 'GRABPAY'];
        $ewalletType = strtoupper($request->ewallet_type);

        if (!in_array($ewalletType, $allowedChannels)) {
            return response()->json([
                'message' => 'Invalid e-wallet type. Allowed values: GCASH, SHOPEEPAY, DANA, OVO, LINKAJA, GRABPAY.'
            ], 400);
        }

        $channelMapping = [
            'GCASH' => 'PH_GCASH',
            'SHOPEEPAY' => 'PH_SHOPEEPAY',
            'GRABPAY' => 'PH_GRABPAY',
        ];
        
        $ewalletType = strtoupper($request->ewallet_type);
        
        if (!isset($channelMapping[$ewalletType])) {
            return response()->json([
                'message' => 'Invalid e-wallet type. Allowed values: GCASH, SHOPEEPAY, GRABPAY.'
            ], 400);
        }
        
        $channelCode = $channelMapping[$ewalletType];
        

        $data = [
            "reference_id" => $externalId, 
            "currency" => "PHP", 
            "amount" => $request->amount,
            "checkout_method" => "ONE_TIME_PAYMENT", // REQUIRED: Use "ONE_TIME_PAYMENT" or "TOKENIZED_PAYMENT"
            "channel_code" => $channelCode,
            "channel_properties" => [
                "mobile_number" => $this->formatPhoneNumber($request->phone),
                "success_redirect_url" => url('/payment-success'), // Redirect user after payment
                "failure_redirect_url" => url('/payment-failed')
            ]
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

        Log::info('Xendit E-Wallet Response:', ['response' => $responseData]);

        if (isset($responseData['status']) && in_array($responseData['status'], ['PENDING', 'SUCCEEDED'])) {
            PaymentEwallet::create([
                'external_id' => $responseData['id'],
                'reference_id' => $responseData['reference_id'],
                'email' => $request->email,
                'ewallet_type' => $request->ewallet_type,
                'amount' => $request->amount,
                'status' => $responseData['status'],
                'redirect_url' => $responseData['actions']['mobile_web_checkout_url'] ?? null,
            ]);
        
            return response()->json([
                'message' => 'E-Wallet payment created successfully',
                'checkout_url' => $responseData['actions']['mobile_web_checkout_url'] ?? null,
                'status' => $responseData['status'],
                'reference_id' => $responseData['reference_id']
            ]);
        }        

        return response()->json([
            'message' => 'Failed to create e-wallet payment',
            'error' => $responseData
        ], 500);
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === "0") {
            return "+63" . substr($phone, 1); 
        }

        if (substr($phone, 0, 1) === "+") {
            return $phone;
        }

        return "+63" . $phone;
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
