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
        $portal = Portal::select(['id'])->with([
            'portalPartnerLinks'
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


//        $external_url = str_replace('{tracker}', $portal->name, $portal->partnerLink->url);
//        $external_url = str_replace('{cid}', $portal->id, $external_url);

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

        $external_url = '';
        $portal_partner_link_id = 0;
        foreach ($portal->portalPartnerLinks->sortBy('priority') as $link) {
            $conditions = $link->conditions;

            if (isset($conditions['country'])) {
                $inList = in_array($checkIp['country_code'], $conditions['country']['values']);
                if (($conditions['country']['operator'] === 'in' && !$inList) ||
                    ($conditions['country']['operator'] === 'not' && $inList)) {
                    continue;
                }
            }

            if (isset($conditions['device']) && $conditions['device']['value'] !== $deviceType) {
                continue;
            }

            $external_url = str_replace('{tracker}', $short_url, $link->partnerLink->url);
            $portal_partner_link_id = $link->partnerLink->id;
            break;
        }

        StoreVisitJob::dispatch([
            'deviceType' => $deviceType,
            'ip' => $ip,
            'userAgent' => $userAgent,
            'referrer' => $referrer,
            'visitDate' => $visitDate,
            'country_code' => $checkIp['country_code'],
            'portalId' => $portal->id,
            'portalPartnerLinkId' => $portal_partner_link_id,
        ]);

//return redirect()->away('https://example.com');
        return response()->json([
            'success' => $external_url,
            'checkIp' => $checkIp,
        ]);

    }
}
