<?php

namespace App\Http\Requests\Blog\BlogCategory;

use Domain\Blog\Models\BlogCategory;
use Illuminate\Foundation\Http\FormRequest;

class BlogCategoriesParentUpdateRelationshipsRequest extends FormRequest
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
            'data.id'   => ['required','integer','exists:blog_categories,id'],
            'data.type' => ['required','string','in:' . BlogCategory::TYPE_RESOURCE],
        ];
    }
}
