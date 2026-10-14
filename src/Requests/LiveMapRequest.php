<?php

namespace Azuriom\Plugin\PlugiitLiveMap\Requests;

use Azuriom\Http\Requests\Traits\ConvertCheckbox;
use Azuriom\Plugin\PlugiitLiveMap\Models\LiveMap;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LiveMapRequest extends FormRequest
{
    use ConvertCheckbox;

    /**
     * The attributes represented by checkboxes.
     *
     * @var array<int, string>
     */
    protected array $checkboxes = [
        'is_enabled',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(array_keys(LiveMap::TYPES))],
            'url' => ['required', 'string', 'url:http,https', 'max:500'],
            'server_id' => ['nullable', 'integer', 'exists:servers,id'],
            'icon' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9 _-]+$/i'],
            'position' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'is_enabled' => ['boolean'],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = $this->validator->validated();
        $validated['position'] ??= 0;

        return data_get($validated, $key, $default);
    }
}
