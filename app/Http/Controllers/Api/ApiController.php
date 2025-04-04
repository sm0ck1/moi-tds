<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MakeShortCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\PortalPlacementRequest;
use App\Models\Domain;
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

        if ($portalPlacements->isNotEmpty()) {
            $ids = $portalPlacements->pluck('id');
            PortalPlacement::query()
                ->whereIn('id', $ids)
                ->update(['get_to_ping' => 1]);
        }

        if ($portalPlacements->isEmpty()) {
            return response()->json(['message' => 'No portal placements found.'], 404);
        }

        return response()->json(['links' => $portalPlacements]);
    }

    public function getPortalPlacementsWithDomain(Request $request)
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

        $getDomain = Domain::query()->where('is_active_for_ping', 1)->first();

        if (!$getDomain) {
            return response()->json(['message' => 'No active domain for ping.'], 404);
        }

        $domain = $getDomain->name;


        $portalPlacements = $portalPlacements->map(function ($portalPlacement) use ($domain) {
            $subDomain = MakeShortCode::generateRandomStringLower(2);
            $portalPlacement['link'] = 'https://' . $subDomain . $portalPlacement['id'] . '.' . $domain;
            return $portalPlacement;
        });


        if ($portalPlacements->isNotEmpty()) {
            $ids = $portalPlacements->pluck('id');
            PortalPlacement::query()
                ->whereIn('id', $ids)
                ->update(['get_to_ping' => 1]);
        }

        if ($portalPlacements->isEmpty()) {
            return response()->json(['message' => 'No portal placements found.'], 404);
        }

        return response()->json(['links' => $portalPlacements]);
    }

    public function getPortalPlacementsOnlyForId(PortalPlacement $portalPlacement)
    {
        PortalPlacement::query()
            ->where('id', $portalPlacement->id)
            ->update(['ping_counter' => 1]);

        return response()->json($portalPlacement);
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

    public function addNewLinksToPing(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'token'   => ['required', 'string'],
            'portal_id' => ['required', 'integer'],
            'link'      => ['required', 'string'],
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Invalid request.',
                'errors' => $validated->errors()
                ], 400);
        }
        if ($request->token !== env('APP_TOKEN_API')) {
            return response()->json(['message' => 'Invalid token.'], 403);
        }
        $link = [
            'external_url' => $request->get('link'),
            'portal_id'    => $request->get('portal_id'),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        PortalPlacement::insert($link);
        return response()->json($link);
    }

}
