<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortalPlacement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getPortalPlacements(Request $request)
    {
        $request->validate([
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $links = collect();

        DB::transaction(function () use ($request, &$links) {
            $portalPlacements = PortalPlacement::query()
                ->select('id', 'external_url')
                ->where('ping_counter', 0)
                ->where('get_to_ping', 0)
                ->limit($request->limit)
                ->lockForUpdate()
                ->get();

            if ($portalPlacements->isNotEmpty()) {
                $links = $portalPlacements->pluck('external_url');
                $ids = $portalPlacements->pluck('id');
                PortalPlacement::query()
                    ->whereIn('id', $ids)
                    ->update(['get_to_ping' => 1]);
            }
        });

        if ($links->isEmpty()) {
            return response()->json(['message' => 'No portal placements found.'], 404);
        }

        return response()->json(['links' => $links]);
    }

    public function setSuccessPing(Request $request)
    {
        $request->validate([
            'links' => ['required', 'array'],
            'links.*' => ['required', 'url'],
        ]);

        $links = $request->input('links');

        $updatedRows = DB::table('portal_placements')
            ->whereIn('external_url', $links)
            ->update(['ping_counter' => 1]);

        if ($updatedRows === 0) {
            return response()->json(['message' => 'No matching portal placements found.'], 404);
        }

        return response()->json(['message' => 'Success ping.', 'updated' => $updatedRows]);
    }
}
