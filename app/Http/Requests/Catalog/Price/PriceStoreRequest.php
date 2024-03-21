<?php

namespace App\Http\Requests\Catalog\Price;

use Domain\Catalog\Models\Price;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Foundation\Http\FormRequest;

class PriceStoreRequest extends FormRequest
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
            'data' => ['required']
        ];
    }
}
