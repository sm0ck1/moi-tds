<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class GoogleCalendarService
{
    private const MOBILE_USER_AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.2 Mobile/15E148 Safari/604.1';

    private $service;
    private $calendarId;

    public function __construct()
    {
        try {
            $this->calendarId = env('GOOGLE_CALENDAR_ID', '');

            $client = new Google_Client();
            $client->setApplicationName('My calendar');
            $client->setScopes(Google_Service_Calendar::CALENDAR);
            $client->setAuthConfig(Storage::path(env('GOOGLE_CREDENTIALS_FILE', '')));
            $client->setAccessType('offline');

            $this->service = new Google_Service_Calendar($client);
            Log::info('Календарь инициализирован');
        } catch (Exception $e) {
            Log::error('Ошибка инициализации календаря', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function createEventAndExtractLinks(array$links = []): array
    {
        try {
            // Создаём событие
            $event = $this->createEvent($links);
            $eventId = $event->getId();
            $publicUrl = $event->getHtmlLink();

            Log::info("Создано событие", [
                'event_id' => $eventId,
                'public_url' => $publicUrl
            ]);

            // Получаем ссылки из события
            sleep(1); // Небольшая задержка для обработки события Google
            $links = $this->fetchPublicHtmlAndExtractLinks($publicUrl);

            // Удаляем событие
            $this->deleteEvent($eventId);

            return $links;

        } catch (Exception $e) {
            Log::error("Ошибка при работе с календарем", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function createEvent(array $links = [])
    {
        try {
            $startTime = time() + 3600; // +1 час от текущего времени
            $endTime = $startTime + 3600; // +1 час от времени начала

            $links = collect($links);
            if ($links->isEmpty()) {
                return null;
            }

            // Создаем HTML для описания
            $htmlLinks = $links->map(function ($url) {
                return sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    htmlspecialchars($url),
                    htmlspecialchars($url)
                );
            });
            Log::info("Ссылки для события", [
                'links' => $htmlLinks->toArray()
            ]);

            $description = "Event Description:<br><br>" . $htmlLinks->join("<br>");

            $event = new Google_Service_Calendar_Event([
                'summary' => 'Event ' . date('Y-m-d H:i'),
                'description' => $description,
                'start' => [
                    'dateTime' => date('c', $startTime),
                    'timeZone' => 'UTC',
                ],
                'end' => [
                    'dateTime' => date('c', $endTime),
                    'timeZone' => 'UTC',
                ],
            ]);
            Log::info("Создание события", [
                'calendar_id' => $this->calendarId,
                'event' => $event->toSimpleObject()
            ]);
            return $this->service->events->insert($this->calendarId, $event);

        } catch (Exception $e) {
            Log::error("Ошибка при создании события", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function fetchPublicHtmlAndExtractLinks(string $publicUrl): array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => self::MOBILE_USER_AGENT,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
                'Cache-Control' => 'max-age=0',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1'
            ])
                ->withOptions([
                    'verify' => false,
                    'timeout' => 30,
                    'allow_redirects' => true
                ])
                ->get($publicUrl);

            if (!$response->successful()) {
                Log::error("Ошибка при получении HTML", [
                    'status' => $response->status(),
                    'url' => $publicUrl
                ]);
                return [];
            }

            return $this->extractGoogleLinks($response->body());

        } catch (Exception $e) {
            Log::error("Ошибка при загрузке страницы события", [
                'url' => $publicUrl,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    private function extractGoogleLinks(string $html): array
    {
        try {
            preg_match_all('/https:\/\/www\.google\.com\/url\?[^"]+/', $html, $matches);

            if (empty($matches[0])) {
                return [];
            }

            // Очищаем и сохраняем ссылки
            return array_map(function ($link) {
                return str_replace('amp;', '', $link);
            }, array_unique($matches[0]));

        } catch (Exception $e) {
            Log::error("Ошибка при извлечении ссылок", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function deleteEvent(string $eventId): bool
    {
        try {
            $this->service->events->delete($this->calendarId, $eventId);
            Log::info("Событие удалено", ['event_id' => $eventId]);
            return true;
        } catch (Exception $e) {
            Log::error("Ошибка при удалении события", [
                'event_id' => $eventId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getCalendarViewUrl(): string
    {
        return "https://calendar.google.com/calendar/embed?src=" . urlencode($this->calendarId);
    }
}
