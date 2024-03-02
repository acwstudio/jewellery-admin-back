<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\TypeDevice;

use Domain\Performance\Models\ImageBanner;
use Domain\Performance\Models\TypeDevice;
use Illuminate\Foundation\Http\FormRequest;

class TypeDeviceUpdateRequest extends FormRequest
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
            'data.type'                           => ['required','string','in:' . TypeDevice::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.type'                => ['sometimes','string'],
            'data.attributes.description'         => ['sometimes','string'],
            'data.attributes.slug'                => ['prohibited','string'],
            'data.attributes.is_active'           => ['sometimes','boolean'],
            // relationships
            'data.relationships'                       => ['sometimes','required','array'],
            // one to many imageBanners
            'data.relationships.imageBanners'             => ['sometimes','required','array'],
            'data.relationships.imageBanners.data'        => ['sometimes','required','array'],
            'data.relationships.imageBanners.data.*'      => ['sometimes','required','array'],
            'data.relationships.imageBanners.data.*.type' => ['present','string','in:' . ImageBanner::TYPE_RESOURCE],
            'data.relationships.imageBanners.data.*.id'   => ['present','string', 'distinct', 'exists:image_banners,id'],
        ];
    }
}
