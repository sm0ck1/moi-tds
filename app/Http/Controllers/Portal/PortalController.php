<?php

namespace App\Http\Controllers\Portal;

use App\Helpers\Country;
use App\Helpers\ScanViewsFolder;
use App\Http\Controllers\Controller;
use App\Http\Requests\PortalRequest;
use App\Models\PartnerLink;
use App\Models\Portal;
use App\Models\Topic;
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

    public function create(): \Inertia\Response
    {
        $topics = Topic::all();
        $landings = ScanViewsFolder::landings();
        $countries = (new Country())->getAllCountries();
        return Inertia::render('Portal/PortalCreate', [
            'topics' => $topics,
            'countries' => $countries,
            'landings' => $landings
        ]);
    }

    public function store(PortalRequest $request): \Illuminate\Http\RedirectResponse
    {
        Portal::create($request->all());
        return redirect()->route('portal.index');
    }

    public function edit(Portal $portal): \Inertia\Response
    {
        $landings = ScanViewsFolder::landings();
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
            'countries' => $countries,
            'landings' => $landings
        ]);
    }

    public function update(PortalRequest $request, Portal $portal): \Illuminate\Http\RedirectResponse
    {
        $portal->update($request->all());
        return redirect()->route('portal.edit', $portal)->with('success', 'Portal updated.');
    }

    public function destroy(Portal $portal): \Illuminate\Http\RedirectResponse
    {
        $portal->delete();
        return redirect()->route('portal.index');
    }

    public function restore($id): \Illuminate\Http\RedirectResponse
    {
        Portal::withTrashed()->find($id)->restore();
        return redirect()->route('portal.index');
    }

}
