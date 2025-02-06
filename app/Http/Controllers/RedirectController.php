<?php

namespace App\Http\Controllers;

use App\Jobs\StoreVisitJob;
use App\Models\Portal;
use App\Models\VisitUser;
use App\Services\CheckIp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

class RedirectController extends Controller
{

    public function redirect(Request $request, $short_url)
    {
        $portal = Portal::select('id','partner_link_id')->with([
            'partnerLink' => function ($query) {
                $query->select('id', 'url');
            }
        ])->where('short_url', $short_url)->firstOrFail();

        if (!$portal) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $ipCheck = new CheckIp();

        $ip = $request->get('ip') ?? $request->ip() ?? null;
        $userAgent = $request->get('user_agent') ?? $request->header('User-Agent') ?? null;
        $referrer = $request->header('Referer') ?? $request->get('referrer') ?? '';

        if (empty($userAgent) || empty($ip)) {
            return response()->json(['error' => 'Bad request'], 403);
        }

        $external_url = str_replace('{tracker}', $portal->name, $portal->partnerLink->url);
        $external_url = str_replace('{cid}', $portal->id, $external_url);

//        if($ip == '127.0.0.1') {
//            return response()->json(['success' => $external_url]);
//        }
        $checkIp = $ipCheck->checkIp($ip, $userAgent);
        if (isset($checkIp['error'])) {
            return response()->json(['error' => $checkIp['error']], 429);
        }

        $agent = new Agent();
        $agent->setUserAgent($userAgent);
        $deviceType = $agent->isMobile() || $agent->isTablet() ? 'mobile' : 'desktop';
        $visitDate = Carbon::now()->format('Y-m-d');

        StoreVisitJob::dispatch([
            'deviceType' => $deviceType,
            'ip' => $ip,
            'userAgent' => $userAgent,
            'referrer' => $referrer,
            'visitDate' => $visitDate,
            'portalId' => $portal->id,
            'country_code' => $checkIp['country_code'],
            'partnerLinkId' => $portal->partnerLink->url,
        ]);

//return redirect()->away('https://example.com');
        return response()->json([
            'success' => $external_url,
            'checkIp' => $checkIp,
        ]);

    }
}
