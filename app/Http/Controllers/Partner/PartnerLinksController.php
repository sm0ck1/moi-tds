<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerLinkRequest;
use App\Models\PartnerLink;

class PartnerLinksController extends Controller
{
    public function store(PartnerLinkRequest $request): \Illuminate\Http\RedirectResponse
    {
        PartnerLink::create($request->validated());

        return to_route('partners.edit', $request->partner_id);
    }

    public function update(PartnerLinkRequest $request, PartnerLink $partnerLink): \Illuminate\Http\RedirectResponse
    {
        $partnerLink->update($request->validated());

        return to_route('partners.edit', $partnerLink->partner_id);

    }
}
