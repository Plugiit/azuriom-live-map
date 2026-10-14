{{--
    Embedded map frame.
    Expected variables: $map (LiveMap), $height (int), $allowFullscreen (bool), $showNewTab (bool)
--}}
@once
    @push('styles')
        <link href="{{ plugin_asset('plugiit-live-map', 'css/live-map.css') }}" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="{{ plugin_asset('plugiit-live-map', 'js/live-map.js') }}" defer></script>
    @endpush
@endonce

<div data-plm>
    @if($allowFullscreen || $showNewTab)
        <div class="d-flex flex-wrap justify-content-end gap-2 mb-2">
            @if($allowFullscreen)
                <button type="button" class="btn btn-sm btn-outline-primary" data-plm-fullscreen
                        data-enter-label="{{ trans('plugiit-live-map::messages.fullscreen') }}"
                        data-exit-label="{{ trans('plugiit-live-map::messages.exit_fullscreen') }}">
                    <i class="bi bi-arrows-fullscreen"></i>
                    <span data-plm-fullscreen-label>{{ trans('plugiit-live-map::messages.fullscreen') }}</span>
                </button>
            @endif
            @if($showNewTab)
                <a href="{{ $map->url }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right"></i> {{ trans('plugiit-live-map::messages.new_tab') }}
                </a>
            @endif
        </div>
    @endif

    <div class="plm-map shadow-sm" style="--plm-height: {{ (int) $height }}px">
        <div class="plm-overlay" data-plm-loader>
            <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
            <span class="text-body-secondary">{{ trans('plugiit-live-map::messages.loading') }}</span>
        </div>

        <div class="plm-overlay" data-plm-fallback hidden>
            <i class="bi bi-cone-striped fs-1 text-warning" aria-hidden="true"></i>
            <h2 class="h5 mb-0">{{ trans('plugiit-live-map::messages.fallback.title') }}</h2>
            <p class="mb-1 text-body-secondary">{{ trans('plugiit-live-map::messages.fallback.text') }}</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ $map->url }}" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right"></i> {{ trans('plugiit-live-map::messages.fallback.open') }}
                </a>
                <button type="button" class="btn btn-outline-secondary" data-plm-retry>
                    <i class="bi bi-arrow-clockwise"></i> {{ trans('plugiit-live-map::messages.fallback.retry') }}
                </button>
            </div>
        </div>

        <iframe src="{{ $map->url }}" title="{{ $map->name }}" loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
                @if($allowFullscreen) allowfullscreen allow="fullscreen" @endif></iframe>
    </div>

    <p class="small text-body-secondary mt-2 mb-0">
        <i class="bi bi-info-circle"></i>
        {{ trans('plugiit-live-map::messages.fallback.title') }}
        <a href="{{ $map->url }}" target="_blank" rel="noopener noreferrer">{{ trans('plugiit-live-map::messages.fallback.open') }}</a>
    </p>
</div>
