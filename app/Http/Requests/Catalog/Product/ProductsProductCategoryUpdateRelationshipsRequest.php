<?php

namespace App\Http\Requests\Catalog\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductsProductCategoryUpdateRelationshipsRequest extends FormRequest
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
            'validation' => ['required']
        ];
    }
}
