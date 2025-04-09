<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class GoogleServiceController extends Controller
{
    public function sendToCalendar(Request $request): Response
    {
        $googleService = new GoogleCalendarService;
        $links = $request->input('links');
        $links = collect(explode('!!!', str_replace([
            "\r",
            "\n",
            "\r\n",
        ], '!!!', $links)))->filter()->map(function ($link) {
            return trim($link);
        })->toArray();

        try {
            $result = $googleService->createEventAndExtractLinks($links);

            return back()->with('links', $result);

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return back();
    }
}
