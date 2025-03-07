<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SignatureHelper
{
    private static function getPrivateKey()
    {
        $privateKeyPath = storage_path('keys/bc.priv');
        return openssl_pkey_get_private(file_get_contents($privateKeyPath));
    }

    private static function getPublicKey()
    {
        $publicKeyPath = storage_path('keys/fixed_public.pem');
        return openssl_pkey_get_public(file_get_contents($publicKeyPath));
    }


    public static function generateSignature($clientId, $timestamp, $generateToken = false)
    {
        $privateKey = self::getPrivateKey();
        if (!$privateKey) {
            throw new Exception("Private Key tidak valid.");
        }

        // Pastikan format stringToSign benar
        $stringToSign = trim($clientId . "|" . $timestamp);
        Log::info("String to Sign (Generate): " . $stringToSign);

        // Sign dengan SHA256withRSA
        if (!openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new Exception("Gagal menandatangani string.");
        }

        // Log hasil signature sebelum encode
        Log::info("Signature (raw): " . bin2hex($signature));

        $encodedSignature = base64_encode($signature);

        // Jika butuh access token, buat dari signature + string random
        if ($generateToken) {
            return hash('sha256', $encodedSignature . Str::random(10));
        }

        return $encodedSignature;
    }

    public static function verifySignature($clientId, $timestamp, $signature)
    {
        $publicKey = self::getPublicKey();
        if (!$publicKey) {
            throw new Exception("Public Key tidak valid.");
        }

        // Format ulang stringToSign
        $stringToSign = trim($clientId . "|" . $timestamp);
        Log::info("String to Sign (Verify): " . $stringToSign);

        // Decode signature dari base64
        $decodedSignature = base64_decode($signature);

        if ($decodedSignature === false) {
            throw new Exception("Format signature tidak valid.");
        }

        // Log signature yang diterima
        Log::info("Signature (decoded): " . bin2hex($decodedSignature));

        // Verifikasi signature dengan public key
        $result = openssl_verify($stringToSign, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256);

        Log::info("Verification Result: " . ($result === 1 ? "Valid" : "Invalid"));

        return $result === 1;
    }





    // public static function generateSignature($clientId, $privateKey)
    // {
    //     // Generate Timestamp (pastikan formatnya sesuai)
    //     date_default_timezone_set('UTC');
    //     $xTimestamp = gmdate("Y-m-d\TH:i:s.000\Z");

    //     // Buat stringToSign
    //     $stringToSign = $clientId . "|" . $xTimestamp;

    //     // Generate Signature dengan private key
    //     openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
    //     $xSignature = base64_encode($signature);

    //     return [
    //         'signature' => $xSignature,
    //         'timestamp' => $xTimestamp
    //     ];
    // }
}
