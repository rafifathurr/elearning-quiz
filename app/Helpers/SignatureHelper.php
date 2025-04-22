<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SignatureHelper
{
    private static function getPrivateKey()
    {
        $privateKeyPath = storage_path('keys/private_key.txt');
        return openssl_pkey_get_private(file_get_contents($privateKeyPath));
    }

    private static function getPublicKey()
    {
        $publicKeyPath = storage_path('keys/publickey.pem');
        return openssl_pkey_get_public(file_get_contents($publicKeyPath));
    }


    public static function generateSignature($clientId, $timestamp)
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





    // Start Test Signature HMAC
    public static function generateSignatureV2($method, $endpoint, $accessToken, $body, $timestamp)
    {
        $clientSecret = env('SANDBOX_CLIENT_SECRET');

        if (!$clientSecret) {
            throw new Exception("Client Secret tidak ditemukan.");
        }

        $hashedBody = self::hashRequestBody($body);

        $stringToSign = strtoupper($method) . ':' . $endpoint . ':' . $accessToken . ':' . $hashedBody . ':' . $timestamp;

        Log::info("String to Sign: $stringToSign");

        $signature = hash_hmac('sha512', $stringToSign, $clientSecret, true);
        $signatureBase64 = base64_encode($signature);

        return [
            'xSignature' => $signatureBase64,
            'xPayload' => $stringToSign,
        ];
    }

    private static function hashRequestBody($body)
    {
        if (empty($body)) {
            return '';
        }

        $minifiedBody = json_encode(json_decode($body, true), JSON_UNESCAPED_SLASHES);
        return strtolower(hash('sha256', $minifiedBody));
    }

    public static function verifySignatureV2($method, $endpoint, $accessToken, $body, $timestamp, $receivedSignature)
    {
        $clientSecret = env('SANDBOX_CLIENT_SECRET');

        if (!$clientSecret) {
            throw new Exception("Client Secret tidak ditemukan.");
        }

        // Generate ulang signature dengan data yang sama
        $hashedBody = self::hashRequestBody($body);
        $stringToSign = strtoupper($method) . ':' . $endpoint . ':' . $accessToken . ':' . $hashedBody . ':' . $timestamp;

        Log::info("String to Verify: $stringToSign");

        // Hasil signature yang seharusnya
        $expectedSignature = base64_encode(hash_hmac('sha512', $stringToSign, $clientSecret, true));

        // Bandingkan
        if (hash_equals($expectedSignature, $receivedSignature)) {
            return [
                'verified' => true,
                'expected_signature' => $expectedSignature,
                'payload' => $stringToSign,
            ];
        } else {
            return [
                'verified' => false,
                'expected_signature' => $expectedSignature,
                'received_signature' => $receivedSignature,
                'payload' => $stringToSign,
            ];
        }
    }



    // public static function generateSignatureV2($method, $endpoint, $accessToken, $body, $timestamp)
    // {
    //     $clientSecret = env('BRI_SECRET_KEY'); //CLIENT SECRET BELUM ADA
    //     if (!$clientSecret) {
    //         throw new Exception("Client Secret tidak ditemukan.");
    //     }
    //     $clientSecretBinary = base64_decode($clientSecret);

    //     // 1. Minify JSON Body & Hash SHA-256
    //     $hashedBody = self::hashRequestBody($body);

    //     // 2. Format StringToSign
    //     $stringToSign = strtoupper($method) . ":" . $endpoint . ":" . $accessToken . ":" . $hashedBody . ":" . $timestamp;

    //     Log::info("String to Sign: " . $stringToSign);

    //     // 3. Hash dengan HMAC-SHA512
    //     $signature = hash_hmac('sha512', $stringToSign, $clientSecretBinary, true);

    //     $signatureBase64 = base64_encode($signature);

    //     Log::info("Generated Signature: " . $signatureBase64);

    //     $data = [
    //         'xSignature' => $signatureBase64,
    //         'xPayload' => $stringToSign,
    //     ];

    //     return $data;
    // }
    // private static function hashRequestBody($body)
    // {
    //     if (empty($body)) {
    //         return "";
    //     }

    //     // Minify JSON (hapus spasi & newline)
    //     $minifiedBody = json_encode(json_decode($body, true), JSON_UNESCAPED_SLASHES);

    //     // Hash dengan SHA-256 lalu ubah ke lowercase hex
    //     return strtolower(hash('sha256', $minifiedBody));
    // }
    // End Test Signature HMAC



    // public static function generateSignature($clientId, $timestamp, $generateToken = false)
    // {
    //     $privateKey = self::getPrivateKey();
    //     if (!$privateKey) {
    //         throw new Exception("Private Key tidak valid.");
    //     }

    //     // Pastikan format stringToSign benar
    //     $stringToSign = trim($clientId . "|" . $timestamp);
    //     Log::info("String to Sign (Generate): " . $stringToSign);

    //     // Sign dengan SHA256withRSA
    //     if (!openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
    //         throw new Exception("Gagal menandatangani string.");
    //     }

    //     // Log hasil signature sebelum encode
    //     Log::info("Signature (raw): " . bin2hex($signature));

    //     $encodedSignature = base64_encode($signature);

    //     // Jika butuh access token, buat dari signature + string random
    //     if ($generateToken) {
    //         return hash('sha256', $encodedSignature . Str::random(10));
    //     }

    //     return $encodedSignature;
    // }


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

    // public static function generateSignatureV2($method, $endpoint, $accessToken, $body, $timestamp)
    // {
    //     $clientSecret = env('BRI_SECRET_KEY'); //CLIENT SECRET BELUM ADA
    //     if (!$clientSecret) {
    //         throw new Exception("Client Secret tidak ditemukan.");
    //     }

    //     // 1. Minify JSON Body & Hash SHA-256
    //     $hashedBody = self::hashRequestBody($body);

    //     // 2. Format StringToSign
    //     $stringToSign = strtoupper($method) . ":" . $endpoint . ":" . $accessToken . ":" . $hashedBody . ":" . $timestamp;

    //     Log::info("String to Sign: " . $stringToSign);

    //     // 3. Hash dengan HMAC-SHA512
    //     $signature = hash_hmac('sha512', $stringToSign, $clientSecret);

    //     Log::info("Generated Signature: " . $signature);

    //     return $signature;
    // }
}
