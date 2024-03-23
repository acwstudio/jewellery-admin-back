<?php

namespace App\Http\Requests\Catalog\Price;

use Domain\Catalog\Models\SizeCategory;
use Illuminate\Foundation\Http\FormRequest;

class PricesSizeCategoryUpdateRelationshipsRequest extends FormRequest
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
            'dummy'      => ['required'],
//            'data'      => ['required', 'array'],
//            'data.id'   => ['required','integer','exists:size_categories,id'],
//            'data.type' => ['required','string','in:' . SizeCategory::TYPE_RESOURCE],
        ];
    }

    public function messages(): array
    {
        return [
            'dummy.required' => 'HasOneThrough updating does not make sense.',
        ];
    }
}
