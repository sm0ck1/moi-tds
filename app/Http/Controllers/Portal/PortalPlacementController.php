<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\PortalPlacementRequest;
use App\Models\Portal;
use App\Models\PortalPlacement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class PortalPlacementController extends Controller
{
    public function pingAgainFiltered(Request $request): \Illuminate\Http\RedirectResponse
    {
        $portalPlacements = QueryBuilder::for(PortalPlacement::class)
            ->allowedFilters(['external_url', 'get_to_ping', 'ping_counter'])
            ->with('portal')
            ->orderByDesc('updated_at')
            ->update([
                'ping_counter' => 0,
                'get_to_ping' => 0,
            ]);

        return redirect()->route('portal-placements.index');
    }

    public function pingIsOkFiltered(Request $request): \Illuminate\Http\RedirectResponse
    {
        $portalPlacements = QueryBuilder::for(PortalPlacement::class)
            ->allowedFilters(['external_url', 'get_to_ping', 'ping_counter'])
            ->with('portal')
            ->orderByDesc('updated_at')
            ->update([
                'ping_counter' => 1,
                'get_to_ping' => 1,
            ]);

        return redirect()->route('portal-placements.index');
    }

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
            ->where('ping_counter', 1)
            ->where('get_to_ping', 1)
            ->count();
        $getToPing = PortalPlacement::query()
            ->where('get_to_ping', 1)
            ->where('ping_counter', 0)
            ->count();

        $portalPlacements = QueryBuilder::for(PortalPlacement::class)
            ->allowedFilters(['external_url', 'get_to_ping', 'ping_counter'])
            ->with('portal')
            ->orderByDesc('updated_at');

        $totalPortalPlacements = $portalPlacements->count();

        return Inertia::render('PortalPlacement/PortalPlacements', [
            'portalPlacements' => $portalPlacements->paginate(30),
            'totalPortalPlacements' => $totalPortalPlacements,
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
            'portals' => $portals,
        ]);
    }

    public function store(PortalPlacementRequest $request)
    {
        $validated = $request->validated();
        $links = collect($validated['external_links'])->map(function ($link) use ($validated) {
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

    public function pingAgain(Request $request, PortalPlacement $portalPlacement): \Illuminate\Http\JsonResponse
    {
        $portalPlacement->update([
            'ping_counter' => 0,
            'get_to_ping' => 0,
        ]);

        return response()->json(['message' => 'Success']);
    }
}
