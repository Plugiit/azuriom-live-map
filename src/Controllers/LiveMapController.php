<?php

namespace Azuriom\Plugin\PlugiitLiveMap\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\PlugiitLiveMap\Models\LiveMap;

class LiveMapController extends Controller
{
    /**
     * Show the default map.
     */
    public function index()
    {
        return $this->display(LiveMap::defaultMap());
    }

    /**
     * Show the given map.
     */
    public function show(int $map)
    {
        $liveMap = LiveMap::enabledMaps()->firstWhere('id', $map);

        abort_if($liveMap === null, 404);

        return $this->display($liveMap);
    }

    protected function display(?LiveMap $current)
    {
        $maps = LiveMap::enabledMaps();
        $maps->load('server');

        return view('plugiit-live-map::index', [
            'maps' => $maps,
            'current' => $current !== null ? $maps->firstWhere('id', $current->id) : null,
            'height' => (int) setting('plugiit-live-map.height', 650),
            'allowFullscreen' => (bool) setting('plugiit-live-map.fullscreen', true),
            'showNewTab' => (bool) setting('plugiit-live-map.new_tab', true),
        ]);
    }
}
