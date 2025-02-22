<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\PortalPlacementRequest;
use App\Models\Portal;
use App\Models\PortalPlacement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortalPlacementController extends Controller
{

    public function index()
    {
        $inSearch = PortalPlacement::query()
            ->where('in_search', true)
            ->count();
        $waitingForPing = PortalPlacement::query()
            ->where('get_to_ping', 0)
            ->where('ping_counter', 0)
            ->count();
        $pinged = PortalPlacement::query()
            ->where('ping_counter',  1)
            ->where('get_to_ping', 1)
            ->count();
        $getToPing = PortalPlacement::query()
            ->where('get_to_ping', 1)
            ->where('ping_counter', 0)
            ->count();

        $portalPlacements = PortalPlacement::query()
            ->with('portal')
            ->paginate(30);


        return Inertia::render('PortalPlacement/PortalPlacements', [
            'portalPlacements' => $portalPlacements,
            'aa' => 'aaa',
            'inSearch' => $inSearch,
            'waitingForPing' => $waitingForPing,
            'pinged' => $pinged,
            'getToPing' => $getToPing,
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

    public function inSearch(Request $request, PortalPlacement $portalPlacement): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'in_search' => 'required|boolean',
        ]);
        $portalPlacement->update([
            'in_search' => $validated['in_search'],
        ]);
        return response()->json(['message' => 'Success']);
    }
}
