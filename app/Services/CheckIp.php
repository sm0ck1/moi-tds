<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckIp
{
    /**
     * Максимальное количество запросов за указанный период.
     */
    private const MAX_REQUESTS = 30;

    /**
     * Временной интервал в секундах (например, 10 секунд).
     */
    private const REQUEST_INTERVAL = 10;

    /**
     * Минимальное и максимальное время жизни кеша (в минутах).
     */
    private const CACHE_LIFETIME = [5, 10];

    /**
     * Проверяет IP-адрес и User-Agent, возвращает информацию о них.
     *
     * @param string $ip
     * @param string|null $userAgent
     * @return array|false
     */
    public function checkIp(string $ip, ?string $userAgent = null)
    {
        if (!$this->isIp($ip)) {
            return ['error' => 'I don\'t like your IP address.'];
        }

        // Генерируем уникальный ключ для rate limiting
        $rateLimitKey = 'rate_limit_' . md5($ip . ($userAgent ?? ''));

        // Проверяем, не превышен ли лимит запросов
        $requestCount = Cache::get($rateLimitKey, 0);
        if ($requestCount >= self::MAX_REQUESTS) {
            Log::warning("Rate limit exceeded for IP: {$ip}, User-Agent: {$userAgent}");
            return ['error' => 'Too many requests. Please try again later.'];
        }

        // Увеличиваем счётчик запросов
        Cache::put($rateLimitKey, $requestCount + 1, self::REQUEST_INTERVAL);

        // Генерируем уникальный ключ для кеша
        $cacheKey = 'ip_info_' . md5($ip . ($userAgent ?? ''));

        // Используем Cache::flexible() для работы с кешем
        return Cache::flexible($cacheKey, self::CACHE_LIFETIME, function () use ($ip, $userAgent) {
            // Логика получения данных
            return $this->fetchIpData($ip, $userAgent);
        });
    }

    /**
     * Получает данные об IP-адресе и User-Agent.
     *
     * @param string $ip
     * @param string|null $userAgent
     * @return array|false
     */
    private function fetchIpData(string $ip, ?string $userAgent): array|false
    {
        // Шаг 1: Проверка User-Agent
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

        // Шаг 2: Проверка через Google's Verification Tool (если это Googlebot)
//        if ($this->isGoogleBot($ip)) {
//            return [
//                'is_bot' => true,
//                'bot_name' => 'Googlebot',
//                'country' => null,
//                'country_code' => null,
//                'source' => 'google_verification_tool',
//            ];
//        }

        // Шаг 3: Запрос к стороннему сервису
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
                Log::error("Failed to fetch IP info for {$ip}: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error occurred while fetching IP info: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Проверяет, является ли строка валидным IP-адресом.
     *
     * @param string $ip
     * @return bool
     */
    private function isIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Определяет бота по User-Agent.
     *
     * @param string|null $userAgent
     * @return array|null
     */
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

    /**
     * Проверяет, является ли IP-адрес Googlebot с помощью Google's Verification Tool.
     *
     * @param string $ip
     * @return bool
     */
    private function isGoogleBot(string $ip): bool
    {
        $hostname = gethostbyaddr($ip);

        // Проверяем, соответствует ли доменное имя паттерну Googlebot
        if (!fnmatch('*.googlebot.com', $hostname)) {
            return false;
        }

        // Выполняем прямой DNS-запрос для подтверждения
        $forwardIp = gethostbyname($hostname);
        if ($forwardIp !== $ip) {
            return false;
        }

        // Дополнительно проверяем через Google's Verification Tool
        $response = Http::get("https://dns.google/resolve", [
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
     * Очищает кеш для указанного IP и User-Agent.
     *
     * @param string $ip
     * @param string|null $userAgent
     */
    public function clearCache(string $ip, ?string $userAgent = null): void
    {
        $cacheKey = 'ip_info_' . md5($ip . ($userAgent ?? ''));
        Cache::forget($cacheKey);
    }
}
