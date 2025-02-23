<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Exception;

class GoogleIndexingHelper
{
    private $keyFile;
    private $token;
    private $email;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setRandomKeyFile();
        $this->token = $this->getToken();
        $this->email = $this->getServiceEmail();
    }

    /**
     * Set random JSON key file from storage/google-auth directory
     *
     * @return void
     * @throws Exception
     */
    private function setRandomKeyFile(): void
    {
        $files = Storage::disk('local')->files('google-auth');
        $jsonFiles = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'json';
        });

        if (empty($jsonFiles)) {
            throw new Exception('No JSON auth files found in storage/google-auth directory');
        }

        $randomFile = $jsonFiles[array_rand($jsonFiles)];
        $this->keyFile = Storage::disk('local')->get($randomFile);
    }

    /**
     * Generate JWT token
     *
     * @param string $scope
     * @return string
     */
    private function generateJwt(string $scope): string
    {
        $keyArr = json_decode($this->keyFile, true);

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $claimSet = [
            'iss' => $keyArr['client_email'],
            'scope' => $scope,
            'aud' => 'https://www.googleapis.com/oauth2/v4/token',
            'exp' => time() + 3600,
            'iat' => time(),
        ];

        $privateKey = $keyArr['private_key'];
        $jwt = $this->encodeBase64Url(json_encode($header)) . '.' .
            $this->encodeBase64Url(json_encode($claimSet));

        openssl_sign($jwt, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return $jwt . '.' . $this->encodeBase64Url($signature);
    }

    /**
     * Encode string to base64 URL safe
     *
     * @param string $data
     * @return string
     */
    private function encodeBase64Url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Get OAuth2 token
     *
     * @param string $jwt
     * @return array
     */
    private function getOauth2Token(string $jwt): array
    {
        $response = Http::asForm()->post('https://www.googleapis.com/oauth2/v4/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        return $response->json();
    }

    /**
     * Get access token
     *
     * @return string
     * @throws Exception
     */
    private function getToken(): string
    {
        $scope = 'https://www.googleapis.com/auth/indexing';
        $jwt = $this->generateJwt($scope);
        $tokenArr = $this->getOauth2Token($jwt);

        if (!isset($tokenArr['access_token'])) {
            throw new Exception("Failed to get access token");
        }

        return $tokenArr['access_token'];
    }

    /**
     * Get service account email
     *
     * @return string
     */
    private function getServiceEmail(): string
    {
        $keyArr = json_decode($this->keyFile, true);
        return $keyArr['client_email'];
    }

    /**
     * Send single URL notification
     *
     * @param string $url
     * @return array
     */
    public function sendUrlNotification(string $url): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ])->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
            'url' => trim($url),
            'type' => 'URL_UPDATED'
        ]);

        return $response->json();
    }

    /**
     * Send batch URL notifications
     *
     * @param array $urls
     * @return array
     */
    public function sendBatchUrlNotification(array $urls): array
    {
        $boundary = "===============" . uniqid() . "==";
        $body = '';

        foreach ($urls as $index => $url) {
            $requestData = json_encode([
                'url' => trim($url),
                'type' => 'URL_UPDATED',
            ]);

            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/http\r\n";
            $body .= "Content-Transfer-Encoding: binary\r\n";
            $body .= "Content-ID: <" . md5($url) . "+{$index}>\r\n\r\n";
            $body .= "POST /v3/urlNotifications:publish\r\n";
            $body .= "Content-Type: application/json\r\n";
            $body .= "accept: application/json\r\n";
            $body .= "content-length: " . strlen($requestData) . "\r\n\r\n";
            $body .= $requestData . "\r\n";
        }

        $body .= "--{$boundary}--";

        $response = Http::withHeaders([
            'Content-Type' => 'multipart/mixed; boundary="' . $boundary . '"',
            'Authorization' => 'Bearer ' . $this->token
        ])->post('https://indexing.googleapis.com/batch', $body);

        return $this->parseResponse($response->body());
    }

    /**
     * Parse batch response
     *
     * @param string $response
     * @return array
     */
    private function parseResponse(string $response): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $results['total'] = substr_count($response, 'URL_UPDATED');
        $results['success'] = $results['total'];
        $results['failed'] = count($urls) - $results['success'];

        return $results;
    }

    /**
     * Get service account email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
