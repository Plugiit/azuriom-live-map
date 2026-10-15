@extends('layouts.app')

@section('title', $current?->name ?? trans('live-map::messages.title'))

@section('content')
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
        <div>
            <h1 class="mb-1"><i class="bi bi-map"></i> {{ trans('live-map::messages.title') }}</h1>
            <p class="text-body-secondary mb-0">{{ trans('live-map::messages.subtitle') }}</p>
        </div>
    </div>

    @if($current === null)
        <div class="alert alert-info" role="status">
            <i class="bi bi-info-circle"></i> {{ trans('live-map::messages.empty') }}
        </div>
    @else
        @if($maps->count() > 1)
            <ul class="nav nav-pills plm-tabs flex-nowrap overflow-auto pb-1 mb-3 d-none d-md-flex" role="tablist">
                @foreach($maps as $map)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($map->id === $current->id) active @endif" href="{{ route('live-map.show', $map->id) }}"
                           @if($map->id === $current->id) aria-current="page" @endif>
                            <i class="{{ $map->iconClass() }}"></i> {{ $map->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mb-3 d-md-none">
                <label class="form-label visually-hidden" for="plmMapSelect">{{ trans('live-map::messages.select') }}</label>
                <select class="form-select" id="plmMapSelect" data-plm-select>
                    @foreach($maps as $map)
                        <option value="{{ route('live-map.show', $map->id) }}" @selected($map->id === $current->id)>{{ $map->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h2 class="h5 mb-0"><i class="{{ $current->iconClass() }}"></i> {{ $current->name }}</h2>
                    <span class="badge text-bg-secondary">{{ trans('live-map::messages.types.'.$current->type) }}</span>

                    @if($current->server !== null)
                        @if($current->server->isOnline())
                            <span class="badge text-bg-success">
                                <i class="bi bi-hdd-network"></i> {{ $current->server->name }} ·
                                {{ trans('live-map::messages.players', ['count' => $current->server->getOnlinePlayers()]) }}
                            </span>
                        @else
                            <span class="badge text-bg-danger">
                                <i class="bi bi-hdd-network"></i> {{ $current->server->name }} · {{ trans('live-map::messages.offline') }}
                            </span>
                        @endif
                    @endif
                </div>

                @include('live-map::_frame', ['map' => $current])
            </div>
        </div>
    @endif
@endsection
