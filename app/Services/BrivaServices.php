<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrivaServices
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->clientId = env('BRI_CLIENT_ID');
        $this->clientSecret = env('BRI_SECRET_KEY');
        $this->baseUrl = env('BRI_BASE_URL', 'https://bratacerdas.com/snap/v1.0');
    }

    public function getAccessToken()
    {
        $response = Http::asForm()->post("{$this->baseUrl}/oauth/client_credential/accesstoken", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);

        Log::info('Raw Token Response:', ['response' => $response->body()]);

        $token = $response->json()['access_token'] ?? null;
        Log::info('BRIVA Access Token:', ['token' => $token]);

        return $token;
    }


    public function inquiry($vaNumber)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("{$this->baseUrl}/v2/inquiry", [
            'va_number' => $vaNumber
        ]);

        Log::info('Raw Response from BRIVA:', ['response' => $response->body()]);

        return $response->json();
    }



    public function makePayment($vaNumber, $amount)
    {
        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)->post("{$this->baseUrl}/transfer-va/payment", [
            'va_number' => $vaNumber,
            'amount' => $amount
        ]);

        return $response->json();
    }
}
