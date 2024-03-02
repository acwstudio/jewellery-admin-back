<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\TypeDevice;

use Domain\Performance\Models\ImageBanner;
use Illuminate\Foundation\Http\FormRequest;

class TypeDeviceImageBannersUpdateRelationshipsRequest extends FormRequest
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
            'data.*.id'   => ['required','string','exists:image_banners,id'],
            'data.*.type' => ['required','string','in:' . ImageBanner::TYPE_RESOURCE],
        ];
    }
}
