<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ZoomService
{
    public function getAccessToken()
    {
        return Cache::remember('zoom_token', 3500, function () {
            $response = Http::withBasicAuth(
                config('services.zoom.client_id'),
                config('services.zoom.client_secret')
            )
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => config('services.zoom.account_id'),
                ]);


            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            throw new \Exception('Failed to get Zoom access token.');
        });
    }
}
