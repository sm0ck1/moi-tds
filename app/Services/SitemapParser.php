<?php

namespace App\Services;

use SimpleXMLElement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Exception;

class SitemapParser
{
    private const BROWSERS = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2.1 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0'
    ];

    /**
     * Парсит индекс sitemap и возвращает список ссылок на карты сайта
     *
     * @throws Exception
     */
    public function parseSitemapIndex(string $url): array
    {
        $content = $this->fetchContent($url);

        if (!$content) {
            throw new Exception("Failed to fetch content from URL: {$url}");
        }

        try {
            $xml = new SimpleXMLElement($content);
        } catch (Exception $e) {
            // Если не удалось распарсить XML, возвращаем исходный URL
            return [
                [
                    'url'  => $url,
                    'date' => null
                ]
            ];
        }

        // Если это индекс карты сайта
        if ($xml->getName() === 'sitemapindex') {
            $sitemaps = [];
            foreach ($xml->sitemap as $sitemap) {
                $sitemaps[] = [
                    'url'  => (string)$sitemap->loc,
                    'date' => $this->parseDate((string)$sitemap->lastmod)
                ];
            }
            return $sitemaps;
        }

        // Если это не индекс, возвращаем исходный URL
        return [
            [
                'url'  => $url,
                'date' => null
            ]
        ];
    }

    /**
     * Парсит отдельную карту сайта и возвращает список URL страниц
     *
     * @throws Exception
     */
    public function parseSitemap(string $url, ?string $pattern = null): array
    {
        $content = $this->fetchContent($url);

        if (!$content) {
            throw new Exception("Failed to fetch content from URL: {$url}");
        }

        try {
            $xml = new SimpleXMLElement($content);
        } catch (Exception $e) {
            throw new Exception("Failed to parse XML content: " . $e->getMessage());
        }

        if ($xml->getName() !== 'urlset') {
            throw new Exception("The provided URL is not a sitemap");
        }

        $urls = [];
        foreach ($xml->url as $urlNode) {
            $pageUrl = (string)$urlNode->loc;

            // Если задан pattern, проверяем соответствие
            if ($pattern && !preg_match($pattern, $pageUrl)) {
                continue;
            }

            $urls[] = [
                'url'  => $pageUrl,
                'date' => $this->parseDate((string)$urlNode->lastmod)
            ];
        }

        return $urls;
    }

    private function fetchContent(string $url): ?string
    {
        try {
            // Случайный выбор браузера
            $browser = $this->getRandomBrowser();

            $response = Http::withHeaders([
                'User-Agent' => $browser,
            ])
                ->timeout(30)
                ->get($url);
            if ($response->status() === 403) {
                dd($response);
            }
            return $response->successful() ? $response->body() : null;
        } catch (Exception) {
            return null;
        }
    }

    private function parseDate(?string $date): ?string
    {
        if (!$date) return null;

        try {
            return Carbon::parse($date)->toIso8601String();
        } catch (Exception) {
            return null;
        }
    }

    private function getRandomBrowser(): string
    {
        return self::BROWSERS[array_rand(self::BROWSERS)];
    }
}
