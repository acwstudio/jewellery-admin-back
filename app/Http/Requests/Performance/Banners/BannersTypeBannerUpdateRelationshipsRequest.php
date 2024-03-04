<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\Banners;

use Domain\Performance\Models\TypeBanner;
use Illuminate\Foundation\Http\FormRequest;

class BannersTypeBannerUpdateRelationshipsRequest extends FormRequest
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
            'data.id'   => ['required','integer','exists:type_banners,id'],
            'data.type' => ['required','string','in:' . TypeBanner::TYPE_RESOURCE],
        ];
    }
}
