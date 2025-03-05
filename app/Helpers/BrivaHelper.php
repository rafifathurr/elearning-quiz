<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BrivaHelper
{
    public static function generateSignature($clientId)
    {
        $privateKeyPath = storage_path('keys/bc_pkcs8_clean.priv');

        if (!File::exists($privateKeyPath)) {
            throw new \Exception('Private key file not found.');
        }

        // Baca private key
        $privateKeyContent = File::get($privateKeyPath);
        $privateKey = openssl_pkey_get_private($privateKeyContent);

        if (!$privateKey) {
            throw new \Exception('Invalid private key format or parsing failed.');
        }

        // Format timestamp sesuai dokumentasi (yyyy-MM-ddTHH:mm:ss.SSSZ)
        date_default_timezone_set('UTC');
        $microtime = microtime(true);
        $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);
        $xTimestamp = gmdate("Y-m-d\TH:i:s.v\Z", $microtime);

        // Buat stringToSign
        $stringToSign = $clientId . '|' . $xTimestamp;

        // Pastikan stringToSign sesuai
        // Log untuk debug
        Log::info("stringToSign: " . $stringToSign);

        // Sign stringToSign dengan RSA-SHA256
        $signSuccess = openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        if (!$signSuccess) {
            throw new \Exception('Failed to generate signature.');
        }

        // Encode signature ke base64 tanpa newline
        $xSignature = base64_encode($signature);
        $xSignature = str_replace(["\r", "\n"], '', $xSignature);

        return [
            'signature' => $xSignature,
            'timestamp' => $xTimestamp,
        ];
    }
}
