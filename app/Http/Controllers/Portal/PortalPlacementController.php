<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\PortalPlacementRequest;
use App\Models\Portal;
use App\Models\PortalPlacement;
use Inertia\Inertia;

class PortalPlacementController extends Controller
{

    public function index()
    {
        $portalPlacements = PortalPlacement::query()
            ->with('portal')
            ->paginate(30);
        return Inertia::render('PortalPlacement/PortalPlacements', [
            'portalPlacements' => $portalPlacements
        ]);
    }

    public function create()
    {
        $portals = Portal::query()->get();
        return Inertia::render('PortalPlacement/PortalPlacementCreatePage', [
            'portals' => $portals
        ]);
    }

    public function store(PortalPlacementRequest $request)
    {
        $validated = $request->validated();
        $links = collect($validated['external_links'])->map(function($link) use ($validated) {
            return [
                'external_url' => $link,
                'portal_id' => $validated['portal_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        PortalPlacement::insert($links);
        return redirect()->route('portal-placements.index');
    }
}
