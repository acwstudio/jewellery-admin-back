<?php

namespace App\Http\Requests\Catalog\Price;

use Domain\Catalog\Models\PriceCategory;
use Illuminate\Foundation\Http\FormRequest;

class PricesPriceCategoryUpdateRelationshipsRequest extends FormRequest
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
            'data.id'   => ['required','integer','exists:price_categories,id'],
            'data.type' => ['required','string','in:' . PriceCategory::TYPE_RESOURCE],
        ];
    }
}
