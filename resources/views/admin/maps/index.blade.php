@extends('admin.layouts.admin')

@section('title', trans('live-map::admin.maps.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            @if($maps->isEmpty())
                <p class="text-muted">{{ trans('live-map::admin.maps.empty') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ trans('messages.fields.name') }}</th>
                            <th scope="col">{{ trans('messages.fields.type') }}</th>
                            <th scope="col">{{ trans('messages.fields.url') }}</th>
                            <th scope="col">{{ trans('live-map::admin.maps.server') }}</th>
                            <th scope="col">{{ trans('live-map::admin.maps.position') }}</th>
                            <th scope="col">{{ trans('messages.fields.enabled') }}</th>
                            <th scope="col">{{ trans('messages.fields.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($maps as $map)
                            <tr>
                                <th scope="row">{{ $map->id }}</th>
                                <td>
                                    <i class="{{ $map->iconClass() }}"></i> {{ $map->name }}
                                    @if($map->id === $defaultMapId)
                                        <span class="badge bg-primary">{{ trans('live-map::admin.maps.default') }}</span>
                                    @endif
                                </td>
                                <td>{{ trans('live-map::messages.types.'.$map->type) }}</td>
                                <td class="text-break"><a href="{{ $map->url }}" target="_blank" rel="noopener noreferrer">{{ Str::limit($map->url, 50) }}</a></td>
                                <td>{{ $map->server?->name ?? '—' }}</td>
                                <td>{{ $map->position }}</td>
                                <td>
                                    <span class="badge bg-{{ $map->is_enabled ? 'success' : 'danger' }}">{{ trans_bool($map->is_enabled) }}</span>
                                </td>
                                <td class="text-nowrap">
                                    @if($map->is_enabled)
                                        <a href="{{ route('live-map.show', $map->id) }}" class="mx-1" title="{{ trans('live-map::admin.maps.view') }}" data-bs-toggle="tooltip" target="_blank"><i class="bi bi-eye"></i></a>
                                    @endif
                                    <a href="{{ route('live-map.admin.maps.edit', $map) }}" class="mx-1" title="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                                    <a href="{{ route('live-map.admin.maps.destroy', $map) }}" class="mx-1" title="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip" data-confirm="delete"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <a class="btn btn-primary" href="{{ route('live-map.admin.maps.create') }}">
                <i class="bi bi-plus-lg"></i> {{ trans('messages.actions.add') }}
            </a>
            <a class="btn btn-outline-secondary" href="{{ route('live-map.admin.settings') }}">
                <i class="bi bi-gear"></i> {{ trans('live-map::admin.nav.settings') }}
            </a>
        </div>
    </div>
@endsection
