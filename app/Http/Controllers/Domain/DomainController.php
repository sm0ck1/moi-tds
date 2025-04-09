<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller;
use App\Http\Requests\DomainCreateRequest;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->has('trashed')) {
            $domains = Domain::onlyTrashed();
        } else {
            $domains = Domain::query();
        }

        return Inertia::render('Domain/DomainIndex', [
            'domains' => $domains->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Domain/DomainCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DomainCreateRequest $request)
    {
        Domain::create($request->validated());

        return redirect()->route('domain.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Domain $domain)
    {
        //
    }

    public function editCheckboxes(Request $request, Domain $domain)
    {
        $validation = Validator::make($request->all(), [
            'is_active_for_ping' => 'nullable|boolean',
            'is_active_for_code' => 'nullable|boolean',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $domain->update($validation->validated());

        return response()->json($domain);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Domain $domain)
    {
        return Inertia::render('Domain/DomainEdit', [
            'domain' => $domain,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DomainCreateRequest $request, Domain $domain)
    {
        $domain->update($request->validated());

        return redirect()->route('domain.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Domain $domain)
    {
        //
    }
}
