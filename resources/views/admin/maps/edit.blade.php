@extends('admin.layouts.admin')

@section('title', trans('live-map::admin.maps.edit', ['name' => $map->name]))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('live-map.admin.maps.update', $map) }}" method="POST">
                @method('PUT')

                @include('live-map::admin.maps._form')

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
                <a href="{{ route('live-map.admin.maps.destroy', $map) }}" class="btn btn-danger" data-confirm="delete">
                    <i class="bi bi-trash"></i> {{ trans('messages.actions.delete') }}
                </a>
            </form>
        </div>
    </div>
@endsection
