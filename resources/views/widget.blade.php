{{--
    Live map widget, to include in any theme view:
        @include('plugiit-live-map::widget')
        @include('plugiit-live-map::widget', ['mapId' => 2, 'height' => 350, 'title' => false])
--}}
@php
    $plmMaps = \Azuriom\Plugin\PlugiitLiveMap\Models\LiveMap::enabledMaps();
    $plmMap = isset($mapId) ? $plmMaps->firstWhere('id', (int) $mapId) : \Azuriom\Plugin\PlugiitLiveMap\Models\LiveMap::defaultMap();
@endphp

@if($plmMap !== null)
    <div class="card plugiit-live-map-widget mb-4">
        <div class="card-body">
            @if($title ?? true)
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <h2 class="card-title h5 mb-0"><i class="{{ $plmMap->iconClass() }}"></i> {{ trans('plugiit-live-map::messages.widget.title') }} · {{ $plmMap->name }}</h2>
                    <a href="{{ route('plugiit-live-map.show', $plmMap->id) }}" class="small">
                        {{ trans('plugiit-live-map::messages.widget.more') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif

            @include('plugiit-live-map::_frame', [
                'map' => $plmMap,
                'height' => $height ?? 400,
                'allowFullscreen' => (bool) setting('plugiit-live-map.fullscreen', true),
                'showNewTab' => false,
            ])
        </div>
    </div>
@endif
