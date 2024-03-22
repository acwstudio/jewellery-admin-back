<?php

namespace App\Http\Requests\Catalog\PriceCategory;

use Domain\Catalog\Models\Price;
use Illuminate\Foundation\Http\FormRequest;

class PriceCategoryPricesUpdateRelationshipsRequest extends FormRequest
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
            'data'        => ['present','array'],
            'data.*.id'   => ['required','string','exists:prices,id'],
            'data.*.type' => ['required','string','in:' . Price::TYPE_RESOURCE],
        ];
    }
}
