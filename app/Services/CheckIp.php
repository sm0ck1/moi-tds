<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckIp
{
    /**
     * The maximum number of requests for this period.
     */
    private const MAX_REQUESTS = 30;

    /**
     * The time interval in seconds (for example, 10 seconds).
     */
    private const REQUEST_INTERVAL = 10;

    /**
     * Minimum and maximum lifetime of the cache (in minutes).
     */
    private const CACHE_LIFETIME = [5, 10];

    public function checkIp(string $ip, ?string $userAgent = null)
    {
        if (! $this->isIp($ip)) {
            return ['error' => 'I don\'t like your IP address.'];
        }

        $cacheKey = 'ip_info_'.md5($ip.($userAgent ?? ''));

        return Cache::flexible($cacheKey, self::CACHE_LIFETIME, function () use ($ip, $userAgent) {
            return $this->fetchIpData($ip, $userAgent);
        });
    }

    /**
     * Receives IP address and User-Agent data.
     */
    private function fetchIpData(string $ip, ?string $userAgent): array|false
    {
        $botInfoFromUserAgent = $this->detectBotByUserAgent($userAgent);
        if ($botInfoFromUserAgent) {
            return [
                'is_bot' => true,
                'bot_name' => $botInfoFromUserAgent['bot_name'],
                'country' => null,
                'country_code' => null,
                'source' => 'user_agent',
            ];
        }

        //        Google's Verification Tool
        //        if ($this->isGoogleBot($ip)) {
        //            return [
        //                'is_bot' => true,
        //                'bot_name' => 'Googlebot',
        //                'country' => null,
        //                'country_code' => null,
        //                'source' => 'google_verification_tool',
        //            ];
        //        }

        $url = "http://ip-api.com/json/{$ip}?fields=country,countryCode";
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $responseData = $response->json();

                return [
                    'is_bot' => false,
                    'bot_name' => null,
                    'country' => $responseData['country'] ?? null,
                    'country_code' => $responseData['countryCode'] ?? null,
                    'source' => 'external_service',
                ];
            } else {
                Log::error("Failed to fetch IP info for {$ip}: ".$response->body());

                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error occurred while fetching IP info: '.$e->getMessage());

            return false;
        }
    }

    private function isIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    private function detectBotByUserAgent(?string $userAgent): ?array
    {
        if (empty($userAgent)) {
            return null;
        }

        // Список известных User-Agent'ов ботов
        $botPatterns = [
            'Googlebot' => '/googlebot/i',
            'YandexBot' => '/yandexbot/i',
            'Bingbot' => '/bingbot/i',
            'DuckDuckGo' => '/duckduckgo/i',
            'Yahoo' => '/yahoo/i',
            'Baidu' => '/baiduspider/i',
            'Other' => '/bot/i',
        ];

        foreach ($botPatterns as $botName => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return ['bot_name' => $botName];
            }
        }

        return null;
    }

    private function isGoogleBot(string $ip): bool
    {
        $hostname = gethostbyaddr($ip);

        // Domain has match with Googlebot pattern
        if (! fnmatch('*.googlebot.com', $hostname)) {
            return false;
        }

        // Direct DNS request for confirmation
        $forwardIp = gethostbyname($hostname);
        if ($forwardIp !== $ip) {
            return false;
        }

        // additional check on Google's Verification Tool
        $response = Http::get('https://dns.google/resolve', [
            'name' => $hostname,
            'type' => 'A',
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            $resolvedIps = array_column($responseData['Answer'] ?? [], 'data');

            return in_array($ip, $resolvedIps, true);
        }

        return false;
    }

    /**
     * Clear cache for target IP and User-Agent.
     */
    public function clearCache(string $ip, ?string $userAgent = null): void
    {
        $cacheKey = 'ip_info_'.md5($ip.($userAgent ?? ''));
        Cache::forget($cacheKey);
    }

    public static function getClientIp(): false|string
    {

        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
        ];
        foreach ($keys as $key) {
            if (! empty($_SERVER[$key])) {
                $array = explode(',', $_SERVER[$key]);
                $ip = trim(end($array));
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return false;

    }
}
