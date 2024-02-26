<?php

namespace App\Http\Requests\Catalog\Product;

use Domain\Catalog\Models\Product;
use Domain\Catalog\Models\ProductCategory;
use Domain\Catalog\Models\Weave;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'data'                                 => ['required','array'],
            'data.type'                            => ['required','string','in:' . Product::TYPE_RESOURCE],
            'data.attributes'                      => ['required','array'],
            'data.attributes.product_category_id'  => ['required','integer'],
            'data.attributes.brand_id'             => ['sometimes','string'],
            'data.attributes.sku'                  => ['required','string'],
            'data.attributes.name'                 => ['sometimes','string'],
            'data.attributes.summary'              => ['required','string'],
            'data.attributes.description'          => ['sometimes','string'],
            'data.attributes.slug'                 => ['prohibited'],
            'data.attributes.weight'               => ['sometimes','string'],
            'data.attributes.is_active'            => ['required','boolean'],
            // relationships
            'data.relationships'                     => ['sometimes','required','array'],
            // many to many weaves
            'data.relationships.weaves'             => ['sometimes','required','array'],
            'data.relationships.weaves.data'        => ['sometimes','required','array'],
            'data.relationships.weaves.data.*'      => ['sometimes','required','array'],
            'data.relationships.weaves.data.*.type' => ['present','string','in:' . Weave::TYPE_RESOURCE],
            'data.relationships.weaves.data.*.id'   => ['present','string', 'distinct', 'exists:weaves,id'],
            // many to one productCategory
            'data.relationships.productCategory'           => ['sometimes','required','array'],
            'data.relationships.productCategory.data'      => ['sometimes','array'],
            'data.relationships.productCategory.data.type' => [
                'sometimes','required','string','in:' . ProductCategory::TYPE_RESOURCE
            ],
            'data.relationships.productCategory.data.id'   => [
                'sometimes','required','integer', 'exists:product_categories,id'
            ],
        ];
    }
}
