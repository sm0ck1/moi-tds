<?php

namespace App\Jobs;

use App\Events\VisitUserEvent;
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

    public function handle(): void
    {

        $validator = Validator::make($this->data, [
            'ip'        => 'required',
            'userAgent' => 'required|string',
            'portalId'  => 'required|integer',
            'visitDate' => 'required',
        ]);
        if ($validator->fails()) {
            Log::error('StoreVisitJob validation failed', $validator->errors()->toArray());
            return;
        }

        if (VisitUser::where('uniq_user_hash', $this->data['uniq_user_hash'])->exists()) {
            VisitUser::where('uniq_user_hash', $this->data['uniq_user_hash'])->increment('visit_count');
            return;
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
            'uniq_user_hash'         => $this->data['uniq_user_hash'],
            'external_url'           => $this->data['external_url'] ?? '',
            'tracker'                => $this->data['tracker'],
            'metrics'                => $this->data['metrics'],
        ]);

        event(new VisitUserEvent('User visited portal.'));

    }
}
