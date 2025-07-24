<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Request;

class EncryptService
{
    // Servicio - solo lógica
    public function decrypt($request, $aesKeyHeader)
    {
        try {
            $ciphertextBase64 = $request->input('ciphertext');
            $ivBase64 = $request->input('iv');

            Log::info('Datos recibidos en decrypt', [
                'ciphertext_base64' => $ciphertextBase64,
                'iv_base64' => $ivBase64,
                'aes_key_header' => $aesKeyHeader,
            ]);

            if (!$ciphertextBase64 || !$ivBase64) {
                Log::warning('Faltan ciphertext o iv en la petición');
                return null;
            }

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);
            $aesKey = $aesKeyHeader;

            if ($ciphertext === false || $iv === false || $aesKey === false) {
                Log::error('Error al decodificar base64 de ciphertext, iv o aesKey');
                return null;
            }

            Log::info('Longitudes después de base64_decode', [
                'ciphertext_length' => strlen($ciphertext),
                'iv_length' => strlen($iv),
                'aes_key_length' => strlen($aesKey),
            ]);

            $tagLength = 16;
            if (strlen($ciphertext) < $tagLength) {
                Log::error('Ciphertext demasiado corto para contener tag');
                return null;
            }

            $tag = substr($ciphertext, -$tagLength);
            $ciphertextRaw = substr($ciphertext, 0, -$tagLength);

            Log::info('Tag y ciphertext separados', [
                'tag_hex' => bin2hex($tag),
                'ciphertext_raw_length' => strlen($ciphertextRaw),
            ]);

            $decrypted = openssl_decrypt(
                $ciphertextRaw,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($decrypted === false) {
                Log::error('Fallo el descifrado openssl_decrypt');
                return null;
            }

            Log::info('Texto descifrado correctamente');

            return $decrypted;

        } catch (Exception $e) {
            Log::error('Error en decrypt: ' . $e->getMessage());
            return null;
        }
    }

    public function decryptFile(array $payload, $aesKeyHeader): ?string
    {
        try {
            $ciphertextBase64 = $payload['ciphertext'] ?? null;
            $ivBase64 = $payload['iv'] ?? null;

            if (!$ciphertextBase64 || !$ivBase64) {
                return null;
            }

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);


            $aesKey = base64_decode($aesKeyHeader);
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
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error decrypting file data: ' . $e->getMessage());
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
            Log::error('Error in class: ' . __CLASS__ . ' - Method: ' . __FUNCTION__ . ' - Error enrypting data: ' . $e->getMessage());
        }

    }
}