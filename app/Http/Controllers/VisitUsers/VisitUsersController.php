<?php

namespace App\Http\Controllers\VisitUsers;

use App\Http\Controllers\Controller;
use App\Models\VisitUser;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class VisitUsersController extends Controller
{
    function getVisits()
    {
        $visits = QueryBuilder::for(VisitUser::class)
            ->with(['portal', 'portalPartnerLink', 'portalPartnerLink.partner'])
            ->allowedFilters(['country_code', 'partner_link_id', 'portal_id'])
            ->orderByDesc('id')
            ->paginate(10);

        return Inertia::render('VisitUsers/VisitUsersIndex', [
            'visitUsers' => $visits
        ]);

    }
}
