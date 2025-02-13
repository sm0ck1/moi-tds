<?php

namespace App\Helpers;

class Encryptor {
    private string $key;
    private string $cipher = 'AES-256-CBC';

    public function __construct(string $secretKey)
    {
        $this->key = hash('sha256', $secretKey, true);
    }

    public function encrypt(array $data): string
    {

        $serialized = serialize($data);

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $salt = random_bytes(16);

        $encrypted = openssl_encrypt(
            $serialized,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $combined = $iv . $salt . $encrypted;

        return base64_encode($combined);
    }

    public function decrypt(string $data): array
    {
        $decoded = base64_decode($data);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($decoded, 0, $ivLength);
        $salt = substr($decoded, $ivLength, 16);
        $encrypted = substr($decoded, $ivLength + 16);

        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return unserialize($decrypted);
    }
}
