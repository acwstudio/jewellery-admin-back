<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\ImageBanner;

use Domain\Performance\Models\Banner;
use Domain\Performance\Models\ImageBanner;
use Domain\Performance\Models\TypeDevice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ImageBannerStoreRequest extends FormRequest
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
            'data.type'                           => ['required','string','in:' . ImageBanner::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.type_device_id'      => ['required','integer'],
            'data.attributes.name'                => ['sometimes','string'],
            'data.attributes.extension'           => ['sometimes','string'],
            'data.attributes.size'                => ['sometimes','string'],
            'data.attributes.mime_type'           => ['sometimes','string'],
            'data.attributes.description'         => ['required','string'],
            'data.attributes.model_type'          => ['prohibited','string'],
            'data.attributes.slug'                => ['prohibited'],
            'data.attributes.image_link'          => ['sometimes','string'],
            'data.attributes.content_link'        => ['required','string'],
            'data.attributes.is_active'           => ['required','boolean'],
            // relationships
            'data.relationships'                     => ['sometimes','required','array'],
            // many to many banners
            'data.relationships.banners'             => ['sometimes','required','array'],
            'data.relationships.banners.data'        => ['sometimes','required','array'],
            'data.relationships.banners.data.*'      => ['sometimes','required','array'],
            'data.relationships.banners.data.*.type' => ['present','string','in:' . Banner::TYPE_RESOURCE],
            'data.relationships.banners.data.*.id'   => ['present','string', 'distinct', 'exists:banners,id'],
            // many to one typeDevice
            'data.relationships.typeDevice'           => ['sometimes','required','array'],
            'data.relationships.typeDevice.data'      => ['sometimes','array'],
            'data.relationships.typeDevice.data.type' => ['sometimes','required','string','in:' . TypeDevice::TYPE_RESOURCE],
            'data.relationships.typeDevice.data.id'   => ['sometimes','required','integer', 'exists:type_devices,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
//        $data = $this->data;
//        $data['attributes']['model_type'] = ImageBanner::class;
//        $this->getInputSource()->replace(['data' => $data]);
    }

    protected function passedValidation(): void
    {
//        $data = $this->request->all();
//        data_set($data, 'data.attributes.model_type', ImageBanner::class);
//        $this->getInputSource()->replace(['data' => $data]);
    }
}
