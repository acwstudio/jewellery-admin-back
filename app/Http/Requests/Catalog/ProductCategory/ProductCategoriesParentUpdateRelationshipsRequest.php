<?php

namespace App\Http\Requests\Catalog\ProductCategory;

use Domain\Catalog\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;

class ProductCategoriesParentUpdateRelationshipsRequest extends FormRequest
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
            'data'      => ['required', 'array'],
            'data.id'   => ['required','integer','exists:product_categories,id'],
            'data.type' => ['required','string','in:' . ProductCategory::TYPE_RESOURCE],
        ];
    }
}
