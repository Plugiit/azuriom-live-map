@extends('admin.layouts.admin')

@section('title', trans('live-map::admin.maps.create'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('live-map.admin.maps.store') }}" method="POST">
                @include('live-map::admin.maps._form')

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
