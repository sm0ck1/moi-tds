<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\PortalPartnerLinkRequest;
use App\Models\PortalPartnerLink;
use Illuminate\Support\Facades\DB;

class PortalPartnerLinksController extends Controller
{
    public function storePortalPartnerLinks(PortalPartnerLinkRequest $request)
    {
        $portalId = $request->input('portal_partner_links.0.portal_id');
        $links = collect($request->input('portal_partner_links'));

        DB::transaction(function () use ($portalId, $links) {
            PortalPartnerLink::where('portal_id', $portalId)
                ->whereNotIn('id', $links->pluck('id')->filter())
                ->delete();

            PortalPartnerLink::upsert(
                $links->map(fn ($link) => [
                    'id' => $link['id'] ?? null,
                    'portal_id' => $portalId,
                    'partner_link_id' => $link['partner_link_id'],
                    'conditions' => json_encode($link['conditions']),
                    'priority' => $link['priority'],
                    'is_fallback' => $link['is_fallback']
                ])->all(),
                ['id'],
                ['partner_link_id', 'conditions', 'priority', 'is_fallback']
            );
        });

        return redirect()->route('portal.edit', $portalId)->with('success', 'Portal updated.');
    }
}
