<?php

namespace App\Jobs;

use App\Events\VisitUserEvent;
use App\Helpers\UniqUserHash;
use App\Models\ViewUser;
use App\Models\VisitUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreVisitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {

        $validator = Validator::make($this->data, [
            'ip'        => 'required',
            'userAgent' => 'required|string',
            'portalId'  => 'required|integer',
            'visitDate' => 'required',
        ]);
        if ($validator->fails()) {
            Log::error('StoreVisitJob validation failed', $validator->errors()->toArray());
            return false;
        }

        $uniqUserHash = (new UniqUserHash(
            [
                $this->data['portalId'],
                $this->data['portalPartnerLinkId'],
                $this->data['ip'],
                $this->data['userAgent'],
                $this->data['visitDate']
            ]
        ))->generate();

        if (VisitUser::where('uniq_user_hash', $uniqUserHash)->exists()) {
            VisitUser::where('uniq_user_hash', $uniqUserHash)->increment('visit_count');
            return response()->json(['success' => 'add visit count']);
        }

        VisitUser::create([
            'ip_address'             => $this->data['ip'],
            'user_agent'             => $this->data['userAgent'],
            'referrer'               => $this->data['referrer'],
            'visit_date'             => $this->data['visitDate'],
            'country_code'           => $this->data['country_code'],
            'device_type'            => $this->data['deviceType'],
            'portal_id'              => $this->data['portalId'],
            'portal_partner_link_id' => $this->data['portalPartnerLinkId'],
            'uniq_user_hash'         => $uniqUserHash,
        ]);

        event(new VisitUserEvent('User visited portal.'));
    }
}
