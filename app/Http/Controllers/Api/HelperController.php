<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HelperController extends Controller
{
    public function getShortUrl($length = 6): \Illuminate\Http\JsonResponse
    {

        $shortUrl = new \App\Helpers\MakeShortCode;

        return response()->json([
            'short_url' => $shortUrl->make($length),
        ]);
    }

    public function previewLanding($lending) {}
}
