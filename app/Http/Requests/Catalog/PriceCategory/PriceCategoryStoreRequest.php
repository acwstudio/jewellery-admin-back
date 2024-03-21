<?php

namespace App\Http\Requests\Catalog\PriceCategory;

use Domain\Catalog\Models\Price;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Foundation\Http\FormRequest;

class PriceCategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'data'                      => ['required','array'],
            'data.type'                 => ['required','string','in:' . PriceCategory::TYPE_RESOURCE],
            'data.attributes'           => ['required','array'],
            'data.attributes.name'      => ['required','string'],
            'data.attributes.slug'      => ['prohibited','string'],
            'data.attributes.is_active' => ['required','boolean'],
            // relationships
            'data.relationships'                    => ['sometimes','required','array'],
            // one-to-many prices
            'data.relationships.prices'             => ['sometimes','required','array'],
            'data.relationships.prices.data'        => ['sometimes','required','array'],
            'data.relationships.prices.data.*'      => ['sometimes','required','array'],
            'data.relationships.prices.data.*.type' => ['present','string','in:' . Price::TYPE_RESOURCE],
            'data.relationships.prices.data.*.id'   => ['present','string', 'distinct', 'exists:prices,id'],
        ];
    }
}
