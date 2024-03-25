<?php

namespace App\Http\Requests\Catalog\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductPricesUpdateRelationshipsRequest extends FormRequest
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
            'dummy'       => 'required'
//            'data'      => ['required', 'array'],
//            'data.id'   => ['required','integer','exists:products,id'],
//            'data.type' => ['required','string','in:' . Product::TYPE_RESOURCE],
        ];
    }

    public function messages(): array
    {
        return [
            'dummy.required' => "HasManyThrough updating can't be made with RESTful",
        ];
    }
}
