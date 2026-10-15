{{--
    Live map widget, to include in any theme view:
        @include('live-map::widget')
        @include('live-map::widget', ['mapId' => 2, 'height' => 350, 'title' => false])
--}}
@php
    $plmMaps = \Azuriom\Plugin\LiveMap\Models\LiveMap::enabledMaps();
    $plmMap = isset($mapId) ? $plmMaps->firstWhere('id', (int) $mapId) : \Azuriom\Plugin\LiveMap\Models\LiveMap::defaultMap();
@endphp

@if($plmMap !== null)
    <div class="card plugiit-live-map-widget mb-4">
        <div class="card-body">
            @if($title ?? true)
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <h2 class="card-title h5 mb-0"><i class="{{ $plmMap->iconClass() }}"></i> {{ trans('live-map::messages.widget.title') }} · {{ $plmMap->name }}</h2>
                    <a href="{{ route('live-map.show', $plmMap->id) }}" class="small">
                        {{ trans('live-map::messages.widget.more') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif

            @include('live-map::_frame', [
                'map' => $plmMap,
                'height' => $height ?? 400,
                'allowFullscreen' => (bool) setting('live-map.fullscreen', true),
                'showNewTab' => false,
            ])
        </div>
    </div>
@endif
