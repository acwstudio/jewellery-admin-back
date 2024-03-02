<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\TypeBanner;

use Domain\Performance\Models\Banner;
use Domain\Performance\Models\TypeBanner;
use Illuminate\Foundation\Http\FormRequest;

class TypeBannerStoreRequest extends FormRequest
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
            'data'                                => ['required','array'],
            'data.type'                           => ['required','string','in:' . TypeBanner::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.type'                => ['required','string'],
            'data.attributes.slug'                => ['prohibited'],
            'data.attributes.is_active'           => ['required','boolean'],
            // relationships
            'data.relationships'                       => ['sometimes','required','array'],
            // one to many banners
            'data.relationships.banners'             => ['sometimes','required','array'],
            'data.relationships.banners.data'        => ['sometimes','required','array'],
            'data.relationships.banners.data.*'      => ['sometimes','required','array'],
            'data.relationships.banners.data.*.type' => ['present','string','in:' . Banner::TYPE_RESOURCE],
            'data.relationships.banners.data.*.id'   => ['present','string', 'distinct', 'exists:banners,id'],
        ];
    }
}
