<?php

namespace App\Http\Requests\Catalog\Weave;

use Domain\Catalog\Models\Product;
use Domain\Catalog\Models\Weave;
use Illuminate\Foundation\Http\FormRequest;

class WeaveStoreRequest extends FormRequest
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
            'data.type'                           => ['required','string','in:' . Weave::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.name'                => ['required','string'],
            'data.attributes.slug'                => ['prohibited'],
            'data.attributes.is_active'           => ['required','boolean'],
            // relationships
            'data.relationships'                     => ['sometimes','required','array'],
            // many to many banners
            'data.relationships.products'             => ['sometimes','required','array'],
            'data.relationships.products.data'        => ['sometimes','required','array'],
            'data.relationships.products.data.*'      => ['sometimes','required','array'],
            'data.relationships.products.data.*.type' => ['present','string','in:' . Product::TYPE_RESOURCE],
            'data.relationships.products.data.*.id'   => ['present','string', 'distinct', 'exists:products,id']
        ];
    }
}
