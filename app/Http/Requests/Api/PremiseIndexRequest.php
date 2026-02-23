<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class PremiseIndexRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'status'    => ['nullable', 'string'],
            'type'      => ['nullable', 'string'],
            'rooms'     => ['nullable', 'integer', 'min:0', 'max:10'],
            'price_min' => ['nullable', 'integer', 'min:0'],
            'price_max' => ['nullable', 'integer', 'gt:price_min'],
            'area_min'  => ['nullable', 'numeric', 'min:0'],
            'area_max'  => ['nullable', 'numeric', 'gt:area_min'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'      => ['nullable', 'integer', 'min:1'],
        ];
    }
}
