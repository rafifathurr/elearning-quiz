<?php

namespace App\Helpers;

class SignatureHelper
{
    public static function generateSignature($clientId, $privateKey)
    {
        // Generate Timestamp (pastikan formatnya sesuai)
        date_default_timezone_set('UTC');
        $xTimestamp = gmdate("Y-m-d\TH:i:s.000\Z");

        // Buat stringToSign
        $stringToSign = $clientId . "|" . $xTimestamp;

        // Generate Signature dengan private key
        openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $xSignature = base64_encode($signature);

        return [
            'signature' => $xSignature,
            'timestamp' => $xTimestamp
        ];
    }
}
