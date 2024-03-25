<?php

namespace App\Http\Requests\Catalog\Product;

use Domain\Catalog\Models\Size;
use Illuminate\Foundation\Http\FormRequest;

class ProductSizesUpdateRelationshipsRequest extends FormRequest
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
            'data.*.id'   => ['required','string','exists:sizes,id'],
            'data.*.type' => ['required','string','in:' . Size::TYPE_RESOURCE],
        ];
    }
}
