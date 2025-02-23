<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortalPlacement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\GoogleIndexingHelper;

class ApiController extends Controller
{
    public function getPortalPlacements(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }

        $portalPlacements = PortalPlacement::query()
            ->select('id', 'external_url')
            ->where('ping_counter', 0)
            ->where('get_to_ping', 0)
            ->limit($request->limit)
            ->lockForUpdate()
            ->get();

//        if ($portalPlacements->isNotEmpty()) {
//            $ids = $portalPlacements->pluck('id');
//            PortalPlacement::query()
//                ->whereIn('id', $ids)
//                ->update(['get_to_ping' => 1]);
//        }

        if ($portalPlacements->isEmpty()) {
            return response()->json(['message' => 'No portal placements found.'], 404);
        }

        return response()->json(['links' => $portalPlacements]);
    }

    public function setSuccessPing(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'token'   => ['required', 'string'],
            'links'   => ['required', 'array'],
            'links.*' => ['required', 'url'],
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }
        if ($request->token !== env('APP_TOKEN_API')) {
            return response()->json(['message' => 'Invalid token.'], 403);
        }
        $links = $request->input('links');

        $updatedRows = DB::table('portal_placements')
            ->whereIn('external_url', $links)
            ->update(['ping_counter' => 1]);

        return response()->json(['message' => 'Success ping.', 'updated' => $updatedRows]);
    }

    public function sendLinkToGoogle(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'token'   => ['required', 'string'],
            'links'   => ['required', 'array'],
            'links.*' => ['required', 'url'],
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }
        if ($request->token !== env('APP_TOKEN_API')) {
            return response()->json(['message' => 'Invalid token.'], 403);
        }
        $googleIndexing = new GoogleIndexingHelper();
        $links = $request->input('links');
        $result = $googleIndexing->sendUrlNotification($links[0]);


        return response()->json($result);
    }
}
