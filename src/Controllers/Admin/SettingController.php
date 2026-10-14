<?php

namespace Azuriom\Plugin\PlugiitLiveMap\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\PlugiitLiveMap\Models\LiveMap;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function show()
    {
        return view('plugiit-live-map::admin.settings', [
            'maps' => LiveMap::orderBy('position')->orderBy('id')->get(),
            'height' => (int) setting('plugiit-live-map.height', 650),
            'defaultMap' => (int) setting('plugiit-live-map.default_map'),
            'fullscreen' => (bool) setting('plugiit-live-map.fullscreen', true),
            'newTab' => (bool) setting('plugiit-live-map.new_tab', true),
        ]);
    }

    /**
     * Update the settings.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'height' => ['required', 'integer', 'min:250', 'max:2000'],
            'default_map' => ['nullable', 'integer', 'exists:plugiit_live_map_maps,id'],
        ]);

        Setting::updateSettings([
            'plugiit-live-map.height' => $validated['height'],
            'plugiit-live-map.default_map' => $validated['default_map'] ?? null,
            'plugiit-live-map.fullscreen' => $request->boolean('fullscreen'),
            'plugiit-live-map.new_tab' => $request->boolean('new_tab'),
        ]);

        ActionLog::log('plugiit-live-map.settings.updated');

        return to_route('plugiit-live-map.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
