<?php

namespace App\Http\Controllers\Portal;

use App\Helpers\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\PortalRequest;
use App\Models\PartnerLink;
use App\Models\Portal;
use App\Models\Topic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortalController extends Controller
{
    public function index()
    {

        if (request()->has('trashed')) {
            $portals = Portal::onlyTrashed();
        } else {
            $portals = Portal::query();
        }
        $portals = $portals->with(['topic'])->withCount(['portalPartnerLinks'])->get();
        return Inertia::render('Portal/PortalIndex', [
            'portals' => $portals
        ]);
    }

    public function show(Portal $portal)
    {
        $portal->load(['topic', 'portalPartnerLinks']);
        $partnerLinks = PartnerLink::with(['partner'])->get();

        return Inertia::render('Portal/PortalShow', [
            'portal' => $portal,
            'partnerLinks' => $partnerLinks
        ]);
    }

    public function create()
    {
        $topics = Topic::all();
        $countries = (new Country())->getAllCountries();
        return Inertia::render('Portal/PortalCreate', [
            'topics' => $topics,
            'countries' => $countries
        ]);
    }

    public function store(PortalRequest $request)
    {
        Portal::create($request->all());
        return redirect()->route('portal.index');
    }

    public function edit(Portal $portal)
    {
        $topics = Topic::all();
        $countries = (new Country())->getAllCountries();
        $portal->load(['portalPartnerLinks' => function ($query) {
            $query->orderBy('priority');
        }]);
        $partnerLinks = PartnerLink::with(['partner', 'topic'])->get();
        return Inertia::render('Portal/PortalEdit', [
            'portal' => $portal,
            'topics' => $topics,
            'partnerLinks' => $partnerLinks,
            'countries' => $countries
        ]);
    }

    public function update(PortalRequest $request, Portal $portal)
    {
        $portal->update($request->all());
        return redirect()->route('portal.edit', $portal)->with('success', 'Portal updated.');
    }

    public function destroy(Portal $portal)
    {
        $portal->delete();
        return redirect()->route('portal.index');
    }

    public function restore($id)
    {
        Portal::withTrashed()->find($id)->restore();
        return redirect()->route('portal.index');
    }

}
