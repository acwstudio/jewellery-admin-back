<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\ImageBanner;

use Domain\Performance\Models\TypeDevice;
use Illuminate\Foundation\Http\FormRequest;

class ImageBannersTypeDeviceUpdateRelationshipsRequest extends FormRequest
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
            'data.id'   => ['required','integer','exists:type_devices,id'],
            'data.type' => ['required','string','in:' . TypeDevice::TYPE_RESOURCE],
        ];
    }
}
