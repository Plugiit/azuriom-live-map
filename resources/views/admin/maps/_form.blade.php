@csrf

<div class="row gx-3">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="nameInput">{{ trans('messages.fields.name') }}</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameInput" name="name" value="{{ old('name', $map->name ?? '') }}" maxlength="100" required>

        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="typeSelect">{{ trans('plugiit-live-map::admin.maps.type') }}</label>
        <select class="form-select @error('type') is-invalid @enderror" id="typeSelect" name="type" required>
            @foreach($types as $type)
                <option value="{{ $type }}" @selected(old('type', $map->type ?? 'bluemap') === $type)>
                    {{ trans('plugiit-live-map::messages.types.'.$type) }}
                </option>
            @endforeach
        </select>

        @error('type')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="urlInput">{{ trans('plugiit-live-map::admin.maps.url') }}</label>
    <div class="input-group @error('url') has-validation @enderror">
        <input type="url" class="form-control @error('url') is-invalid @enderror" id="urlInput" name="url" value="{{ old('url', $map->url ?? '') }}" maxlength="500" placeholder="https://map.example.com/" required>
        <button type="button" class="btn btn-outline-info" id="testMapButton">
            <i class="bi bi-plug"></i> {{ trans('plugiit-live-map::admin.maps.test') }}
        </button>

        @error('url')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="form-text">{{ trans('plugiit-live-map::admin.maps.url_info') }}</div>

    <div id="testMapResult" class="alert mt-2 mb-0 d-none" role="status"></div>
</div>

<div class="row gx-3">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="serverSelect">{{ trans('plugiit-live-map::admin.maps.server') }}</label>
        <select class="form-select @error('server_id') is-invalid @enderror" id="serverSelect" name="server_id">
            <option value="">{{ trans('plugiit-live-map::admin.maps.no_server') }}</option>
            @foreach($servers as $server)
                <option value="{{ $server->id }}" @selected((int) old('server_id', $map->server_id ?? 0) === $server->id)>{{ $server->name }}</option>
            @endforeach
        </select>

        @error('server_id')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="iconInput">{{ trans('plugiit-live-map::admin.maps.icon') }}</label>
        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="iconInput" name="icon" value="{{ old('icon', $map->icon ?? '') }}" maxlength="100" placeholder="bi bi-map">
        <div class="form-text">{{ trans('plugiit-live-map::admin.maps.icon_info') }}</div>

        @error('icon')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label" for="positionInput">{{ trans('plugiit-live-map::admin.maps.position') }}</label>
        <input type="number" min="0" max="10000" class="form-control @error('position') is-invalid @enderror" id="positionInput" name="position" value="{{ old('position', $map->position ?? 0) }}">

        @error('position')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="mb-3 form-check form-switch">
    <input type="checkbox" class="form-check-input" id="enableSwitch" name="is_enabled" @checked(old('is_enabled', $map->is_enabled ?? true))>
    <label class="form-check-label" for="enableSwitch">{{ trans('plugiit-live-map::admin.maps.enable') }}</label>
</div>

@push('footer-scripts')
    <script>
        (function () {
            const button = document.getElementById('testMapButton');
            const input = document.getElementById('urlInput');
            const result = document.getElementById('testMapResult');
            const label = button.innerHTML;
            const classes = { success: 'alert-success', warning: 'alert-warning', error: 'alert-danger' };

            function show(status, messages) {
                result.className = 'alert mt-2 mb-0 ' + (classes[status] || 'alert-danger');
                result.replaceChildren();
                const list = document.createElement('ul');
                list.className = 'mb-0 ps-3';
                messages.forEach(function (message) {
                    const item = document.createElement('li');
                    item.textContent = message;
                    list.appendChild(item);
                });
                result.appendChild(list);
            }

            button.addEventListener('click', function () {
                if (!input.reportValidity()) {
                    return;
                }

                button.disabled = true;
                button.textContent = @json(trans('plugiit-live-map::admin.maps.testing'));

                fetch(@json(route('plugiit-live-map.admin.maps.test')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ url: input.value }),
                }).then(function (response) {
                    return response.json().then(function (data) {
                        if (!response.ok) {
                            const errors = data.errors ? Object.values(data.errors).flat() : [data.message];
                            show('error', errors);
                            return;
                        }
                        show(data.status, data.messages);
                    });
                }).catch(function () {
                    show('error', [@json(trans('plugiit-live-map::admin.test.error'))]);
                }).finally(function () {
                    button.disabled = false;
                    button.innerHTML = label;
                });
            });
        })();
    </script>
@endpush
