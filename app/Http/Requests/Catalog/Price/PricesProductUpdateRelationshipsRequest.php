<?php

namespace App\Http\Requests\Catalog\Price;

use Domain\Catalog\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class PricesProductUpdateRelationshipsRequest extends FormRequest
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
            'data.id'   => ['required','integer','exists:products,id'],
            'data.type' => ['required','string','in:' . Product::TYPE_RESOURCE],
        ];
    }
}
