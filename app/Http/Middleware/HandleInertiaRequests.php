<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'vite_asset_url' => env('APP_URL').':5174',
            'auth' => [
                'user' => $request->user(),
            ],
            'env' => env('APP_ENV', 'dev'),
            'pusher' => [
                'key' => config('broadcasting.connections.pusher.key'),
            ],
            'current_time' => $now = Carbon::now()->format('H:i'),
        ];
    }
}
