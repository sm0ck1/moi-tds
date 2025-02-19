<?php

namespace App\Http\Controllers;

use App\Helpers\Encryptor;
use App\Helpers\UniqUserHash;
use App\Jobs\StoreVisitJob;
use App\Jobs\UpdateVisitJob;
use App\Models\Portal;
use App\Services\CheckIp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class RedirectController extends Controller
{

    public function redirect(Request $request, $short_url)
    {

        $ip = $request->get('ip') ?? $request->ip() ?? null;
        $userAgent = $request->get('user_agent') ?? $request->header('User-Agent') ?? null;
        $referrer = $request->get('referrer') ?? $request->header('Referer') ?? '';
        $tracker = $request->get('t') ?? '';

        if (empty($userAgent) || empty($ip)) {
            return response()->json(['error' => 'Bad request'], 403);
        }

        if (Str::contains(strtolower($userAgent), ['bot', 'spider', 'crawler', 'curl', 'fetch', 'wget', 'slurp', 'python', 'go-http-client', 'client', 'checker', 'google', 'headless'])) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $portal = Portal::select(['id'])->with([
            'portalPartnerLinks'
        ])->where('short_url', $short_url)->firstOrFail();

        if (!$portal) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $ipCheck = new CheckIp();
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

            $external_url = $link->partnerLink->url;

            $portal_partner_link_id = $link->partnerLink->id;
            break;
        }

        $uniqUserHash = (new UniqUserHash(
                [
                    $portal->id,
                    $portal_partner_link_id,
                    $ip,
                    $userAgent,
                    $visitDate
                ]
            ))->generate() . Str::replace('-', '', $visitDate);

        if ($tracker) {
            $external_url = str_replace('{short_link}', $tracker, $external_url);
        }

        $external_url = str_replace('{short_link}', $short_url, $external_url);
        $external_url = str_replace('{uniq_user_hash}', $uniqUserHash, $external_url);

        $data = [
            'uniq_user_hash'      => $uniqUserHash,
            'deviceType'          => $deviceType,
            'ip'                  => $ip,
            'userAgent'           => $userAgent,
            'referrer'            => $referrer,
            'visitDate'           => $visitDate,
            'country_code'        => $checkIp['country_code'],
            'portalId'            => $portal->id,
            'portalPartnerLinkId' => $portal_partner_link_id,
            'tracker'             => $tracker ?? '',
        ];

        $encryptor = new Encryptor(env('ENCRYPT_KEY', 'abracadabra'));
        return response()
            ->view('landings.other.security_page', [
                'url'              => base64_encode($external_url),
                'user_unique_hash' => $uniqUserHash,
                'first_data'          => base64_encode($encryptor->encrypt($data)),
            ])
            ->cookie('cat', $uniqUserHash, 60, null, null, false, false);

//        if (config('app.debug')) {
//            return response()->json($data);
//        }
//        return redirect()->away($external_url);

    }

    public function confirmRedirect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metrics' => 'string',
            'url'     => 'string',
            'uh'      => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Bad request'], 403);
        }
        $data = $validator->validated();

        try {
            $metrics = json_decode(base64_decode($data['metrics']), true);
            $external_url = base64_decode($data['url']);
            $user_unique_hash = $data['uh'];
            if (!$user_unique_hash || !$external_url || !$metrics) {
                return response()->json(['error' => 'Bad request'], 403);
            }

            UpdateVisitJob::dispatch([
                'uniq_user_hash' => $user_unique_hash,
                'metrics'        => $metrics,
                'external_url'   => $external_url,
            ]);


        } catch (\Exception $e) {
            return false;
        } finally {
            return redirect()->away($external_url)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Referrer-Policy', 'no-referrer')
                ->header('Expires', '0');
        }
    }

    public function analytics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metrics' => 'string',
            'uh'      => 'string',
            'fd'      => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Bad request'], 403);
        }
        $data = $validator->validated();

        try {
            $user_unique_hash = $data['uh'];
            $metrics = json_decode(base64_decode($data['metrics']), true);
            $first_data = $data['fd'];

            if (!$user_unique_hash || !$metrics || !$first_data) {
                return response()->json(['error' => 'Bad request'], 403);
            }

            $encryptor = new Encryptor(env('ENCRYPT_KEY', 'abracadabra'));
            $decrypt = $encryptor->decrypt(base64_decode($first_data));
            if (!$decrypt) {
                return response()->json(['error' => 'Bad request'], 403);
            }
            //dd([...$decrypt, 'metrics' => $metrics]);
            StoreVisitJob::dispatch([...$decrypt, 'metrics' => $metrics]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function showLanding()
    {
        return response()
            ->view('landings.dating.only_button', ['data' => []])
            ->cookie('cat', '123', 60, null, null, false, false);
    }
}
