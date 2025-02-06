<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Models\Topic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PartnerController extends Controller
{
    public function index()
    {
        if (request()->has('trashed')) {
            $partners = Partner::onlyTrashed();
        } else {
            $partners = Partner::query();
        }

        $partners = $partners->with(['partnerLinks', 'partnerLinks.topic'])->get();

        return Inertia::render('Partner/PartnerIndex', [
            'partners' => $partners
        ]);
    }

    public function create()
    {
        return Inertia::render('Partner/PartnerCreate');
    }

    public function store(PartnerRequest $request)
    {
        Partner::create($request->validated());
        return redirect()->route('partners.index');
    }

    public function edit(Partner $partner)
    {
        $partner->load(['partnerLinks', 'partnerLinks.topic']);
        $topics = Topic::all();
        return Inertia::render('Partner/PartnerEdit', [
            'partner' => $partner,
            'topics' => $topics
        ]);
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        $partner->update($request->validated());
        return redirect()->route('partners.index');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('partners.index');
    }
}
