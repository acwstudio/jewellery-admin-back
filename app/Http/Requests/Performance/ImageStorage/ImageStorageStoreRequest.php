<?php

declare(strict_types=1);

namespace App\Http\Requests\Performance\ImageStorage;

use Illuminate\Foundation\Http\FormRequest;

class ImageStorageStoreRequest extends FormRequest
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
            'metadata_id' => ['required','string','exists:image_banners,id'],
            'model_type'  => ['required','string'],
            'image'       => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048']
        ];
    }
}
