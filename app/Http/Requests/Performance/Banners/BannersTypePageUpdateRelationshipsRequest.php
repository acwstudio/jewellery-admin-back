<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\Banner;

use Domain\Performance\Models\TypePage;
use Illuminate\Foundation\Http\FormRequest;

class BannersTypePageUpdateRelationshipsRequest extends FormRequest
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
            'data.id'   => ['required','integer','exists:type_pages,id'],
            'data.type' => ['required','string','in:' . TypePage::TYPE_RESOURCE],
        ];
    }
}
