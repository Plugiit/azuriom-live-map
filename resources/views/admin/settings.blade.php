@extends('admin.layouts.admin')

@section('title', trans('live-map::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('live-map.admin.settings.save') }}" method="POST">
                @csrf

                <div class="row gx-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="heightInput">{{ trans('live-map::admin.settings.height') }}</label>
                        <div class="input-group @error('height') has-validation @enderror">
                            <input type="number" min="250" max="2000" class="form-control @error('height') is-invalid @enderror" id="heightInput" name="height" value="{{ old('height', $height) }}" required>
                            <span class="input-group-text">px</span>

                            @error('height')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-text">{{ trans('live-map::admin.settings.height_info') }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="defaultMapSelect">{{ trans('live-map::admin.settings.default_map') }}</label>
                        <select class="form-select @error('default_map') is-invalid @enderror" id="defaultMapSelect" name="default_map">
                            <option value="">{{ trans('live-map::admin.settings.first_map') }}</option>
                            @foreach($maps as $map)
                                <option value="{{ $map->id }}" @selected((int) old('default_map', $defaultMap) === $map->id)>
                                    {{ $map->name }} @unless($map->is_enabled) ({{ trans('live-map::admin.settings.inactive') }}) @endunless
                                </option>
                            @endforeach
                        </select>

                        @error('default_map')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="fullscreenSwitch" name="fullscreen" @checked(old('fullscreen', $fullscreen))>
                    <label class="form-check-label" for="fullscreenSwitch">{{ trans('live-map::admin.settings.fullscreen') }}</label>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="newTabSwitch" name="new_tab" @checked(old('new_tab', $newTab))>
                    <label class="form-check-label" for="newTabSwitch">{{ trans('live-map::admin.settings.new_tab') }}</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h2 class="h5">{{ trans('live-map::admin.settings.widget') }}</h2>
            <p class="mb-2">{{ trans('live-map::admin.settings.widget_info') }}</p>
            <pre class="mb-0"><code>@@include('live-map::widget')
@@include('live-map::widget', ['mapId' => 2, 'height' => 350])</code></pre>
        </div>
    </div>
@endsection
