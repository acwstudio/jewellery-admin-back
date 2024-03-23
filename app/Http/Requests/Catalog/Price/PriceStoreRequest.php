<?php

namespace App\Http\Requests\Catalog\Price;

use Domain\Catalog\Models\Price;
use Domain\Catalog\Models\PriceCategory;
use Domain\Catalog\Models\Size;
use Illuminate\Foundation\Http\FormRequest;

class PriceStoreRequest extends FormRequest
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
            'data'                                => ['required','array'],
            'data.type'                           => ['required','string','in:' . Price::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.price_category_id'   => ['required','integer'],
            'data.attributes.size_id'             => ['required','integer'],
            'data.attributes.value'               => ['required','integer'],
            'data.attributes.is_active'           => ['required','boolean'],
            // relationships
            'data.relationships'                  => ['sometimes','required','array'],
            // one has through product
            'data.relationships.product'          => ['prohibited'],
            // one has through sizeCategory
            'data.relationships.sizeCategory'     => ['prohibited'],
            //   many to one size
            'data.relationships.size'           => ['sometimes','required','array'],
            'data.relationships.size.data'      => ['sometimes','array'],
            'data.relationships.size.data.type' => [
                'sometimes','required','string','in:' . Size::TYPE_RESOURCE],
            'data.relationships.size.data.id'   => ['sometimes','required','integer', 'exists:sizes,id'],
            // many to one priceCategory
            'data.relationships.priceCategory'           => ['sometimes','required','array'],
            'data.relationships.priceCategory.data'      => ['sometimes','array'],
            'data.relationships.priceCategory.data.type' => [
                'sometimes','required','string','in:' . PriceCategory::TYPE_RESOURCE
            ],
            'data.relationships.priceCategory.data.id'   => [
                'sometimes','required','integer','exists:price_categories,id'
            ],
        ];
    }
}
