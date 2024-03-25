<?php

namespace App\Http\Requests\Catalog\Product;

use Domain\Catalog\Models\Weave;
use Illuminate\Foundation\Http\FormRequest;

class ProductsWeavesUpdateRelationshipsRequest extends FormRequest
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
            'data.*.id'   => ['required','string','exists:weaves,id'],
            'data.*.type' => ['required','string','in:' . Weave::TYPE_RESOURCE],
        ];
    }
}
