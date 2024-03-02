<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\Banner;

use Domain\Performance\Models\Banner;
use Domain\Performance\Models\ImageBanner;
use Domain\Performance\Models\TypeBanner;
use Domain\Performance\Models\TypePage;
use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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
            'data.type'                           => ['required','string','in:' . Banner::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.type_banner_id'      => ['sometimes','integer'],
            'data.attributes.type_page_id'        => ['sometimes','integer'],
            'data.attributes.name'                => ['sometimes','string'],
            'data.attributes.description'         => ['sometimes','string'],
            'data.attributes.link'                => ['sometimes','string'],
            'data.attributes.slug'                => ['prohibited'],
            'data.attributes.is_active'           => ['sometimes','boolean'],
            // relationships
            'data.relationships'                     => ['sometimes','required','array'],
            // many to many imageBanners
            'data.relationships.imageBanners'             => ['sometimes','required','array'],
            'data.relationships.imageBanners.data'        => ['sometimes','required','array'],
            'data.relationships.imageBanners.data.*'      => ['sometimes','required','array'],
            'data.relationships.imageBanners.data.*.type' => ['present','string','in:' . ImageBanner::TYPE_RESOURCE],
            'data.relationships.imageBanners.data.*.id'   => [
                'present','string', 'distinct', 'exists:image_banners,id'],
            // many to one typeBanner
            'data.relationships.typeBanner'           => ['sometimes','required','array'],
            'data.relationships.typeBanner.data'      => ['sometimes','array'],
            'data.relationships.typeBanner.data.type' => [
                'sometimes','required','string','in:' . TypeBanner::TYPE_RESOURCE],
            'data.relationships.typeBanner.data.id'   => ['sometimes','required','integer', 'exists:type_banners,id'],
            // many to one typePage
            'data.relationships.typePage'           => ['sometimes','required','array'],
            'data.relationships.typePage.data'      => ['sometimes','array'],
            'data.relationships.typePage.data.type' => [
                'sometimes','required','string','in:' . TypePage::TYPE_RESOURCE],
            'data.relationships.typePage.data.id'   => ['sometimes','required','integer', 'exists:type_pages,id'],
        ];
    }
}
