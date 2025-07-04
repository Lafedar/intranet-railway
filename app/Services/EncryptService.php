<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Request;

class EncryptService
{
    // Servicio - solo lógica
    public function decrypt($request)
    {
        try {
            $ciphertextBase64 = $request->input('ciphertext');
            $ivBase64 = $request->input('iv');

            if (!$ciphertextBase64 || !$ivBase64)
                return null;

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);
            $aesKeyBase64 = $request->session()->get('aes_key');
            if (!$aesKeyBase64)
                return null;

            $aesKey = base64_decode($aesKeyBase64);
            $tagLength = 16;
            if (strlen($ciphertext) < $tagLength)
                return null;

            $tag = substr($ciphertext, -$tagLength);
            $ciphertextRaw = substr($ciphertext, 0, -$tagLength);

            return openssl_decrypt(
                $ciphertextRaw,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            ) ?: null;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error decrypting data: ' . $e->getMessage());

        }
    }
    public function decryptFile(array $payload): ?string
    {
        try {
            $ciphertextBase64 = $payload['ciphertext'] ?? null;
            $ivBase64 = $payload['iv'] ?? null;

            if (!$ciphertextBase64 || !$ivBase64) {
                return null;
            }

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);

            $aesKeyBase64 = session()->get('aes_key');
            if (!$aesKeyBase64) {
                return null;
            }

            $aesKey = base64_decode($aesKeyBase64);
            $tagLength = 16;

            if (strlen($ciphertext) < $tagLength) {
                return null;
            }

            $tag = substr($ciphertext, -$tagLength);
            $ciphertextRaw = substr($ciphertext, 0, -$tagLength);

            return openssl_decrypt(
                $ciphertextRaw,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            ) ?: null;
        } catch (Exception $e) {
            Log::error('Error in ' . __METHOD__ . ': ' . $e->getMessage());
            return null;
        }
    }



    public function encrypt($data, $key, $responseIv)
    {
        try {
            if (is_array($data)) {
                $data = json_encode($data);
            }

            $ciphertextResponse = openssl_encrypt(
                $data,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $responseIv,
                $responseTag
            );

            $ciphertextWithTag = $ciphertextResponse . $responseTag;
            return $ciphertextWithTag;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error enrypting data: ' . $e->getMessage());
        }

    }
}
