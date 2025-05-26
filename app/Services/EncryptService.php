<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class EncryptService
{
    public function decrypt(string $keyBase64, array $ivArray, string $payloadBase64): ?array
    {
        try {
            $key = base64_decode($keyBase64);
            $ciphertext = base64_decode($payloadBase64);
            $iv = implode(array_map("chr", $ivArray)); // array de enteros a string binario

            // Separar el tag (últimos 16 bytes del ciphertext)
            $tag = substr($ciphertext, -16);
            $ciphertextBody = substr($ciphertext, 0, -16);

            // Desencriptar
            $plaintext = openssl_decrypt(
                $ciphertextBody,
                'aes-256-gcm',
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if (!$plaintext) {
                return null;
            }

            return json_decode($plaintext, true);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al desencriptar los datos: ' . $e->getMessage());
            return null;
        }


    }
}
