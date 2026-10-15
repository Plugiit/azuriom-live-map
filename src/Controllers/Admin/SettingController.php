<?php

namespace Azuriom\Plugin\LiveMap\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\LiveMap\Models\LiveMap;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function show()
    {
        return view('live-map::admin.settings', [
            'maps' => LiveMap::orderBy('position')->orderBy('id')->get(),
            'height' => (int) setting('live-map.height', 650),
            'defaultMap' => (int) setting('live-map.default_map'),
            'fullscreen' => (bool) setting('live-map.fullscreen', true),
            'newTab' => (bool) setting('live-map.new_tab', true),
        ]);
    }

    /**
     * Update the settings.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'height' => ['required', 'integer', 'min:250', 'max:2000'],
            'default_map' => ['nullable', 'integer', 'exists:live_map_maps,id'],
        ]);

        Setting::updateSettings([
            'live-map.height' => $validated['height'],
            'live-map.default_map' => $validated['default_map'] ?? null,
            'live-map.fullscreen' => $request->boolean('fullscreen'),
            'live-map.new_tab' => $request->boolean('new_tab'),
        ]);

        ActionLog::log('live-map.settings.updated');

        return to_route('live-map.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
