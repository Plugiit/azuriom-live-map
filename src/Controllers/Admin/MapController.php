<?php

namespace Azuriom\Plugin\LiveMap\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\LiveMap\Models\LiveMap;
use Azuriom\Plugin\LiveMap\Requests\LiveMapRequest;
use Azuriom\Plugin\LiveMap\Support\EmbedTester;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Display a listing of the maps.
     */
    public function index()
    {
        return view('live-map::admin.maps.index', [
            'maps' => LiveMap::with('server')->orderBy('position')->orderBy('id')->get(),
            'defaultMapId' => LiveMap::defaultMap()?->id,
        ]);
    }

    /**
     * Show the form for creating a new map.
     */
    public function create()
    {
        return view('live-map::admin.maps.create', [
            'servers' => Server::all(),
            'types' => array_keys(LiveMap::TYPES),
        ]);
    }

    /**
     * Store a newly created map.
     */
    public function store(LiveMapRequest $request)
    {
        LiveMap::create($request->validated());

        return to_route('live-map.admin.maps.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Show the form for editing the specified map.
     */
    public function edit(LiveMap $map)
    {
        return view('live-map::admin.maps.edit', [
            'map' => $map,
            'servers' => Server::all(),
            'types' => array_keys(LiveMap::TYPES),
        ]);
    }

    /**
     * Update the specified map.
     */
    public function update(LiveMapRequest $request, LiveMap $map)
    {
        $map->update($request->validated());

        return to_route('live-map.admin.maps.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Remove the specified map.
     */
    public function destroy(LiveMap $map)
    {
        $map->delete();

        return to_route('live-map.admin.maps.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Check from the server that a map URL answers and can be embedded.
     */
    public function test(Request $request, EmbedTester $tester)
    {
        $validated = $request->validate([
            'url' => ['required', 'string', 'url:http,https', 'max:500'],
        ]);

        return response()->json($tester->test($validated['url']));
    }
}
