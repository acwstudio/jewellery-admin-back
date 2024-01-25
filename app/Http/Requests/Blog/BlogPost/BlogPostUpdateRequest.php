<?php

namespace App\Http\Requests\Blog\BlogPost;

use Domain\Blog\Models\BlogCategory;
use Domain\Blog\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;

class BlogPostUpdateRequest extends FormRequest
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
            'data.type'                           => ['required','string','in:' . BlogPost::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.blog_category_id'    => ['sometimes','integer'],
            'data.attributes.title'               => ['sometimes','string'],
            'data.attributes.image_id'            => ['sometimes','string'],
            'data.attributes.preview_id'          => ['sometimes','string'],
            'data.attributes.content'             => ['sometimes','string'],
            'data.attributes.status'              => ['sometimes','string'],
            'data.attributes.published_at'        => ['sometimes','date'],
            'data.attributes.description'         => ['sometimes','string'],
            'data.attributes.active'              => ['sometimes','boolean'],
            'data.attributes.is_main'             => ['sometimes','boolean'],
            // relationships
            'data.relationships'                        => ['sometimes','required','array'],
            // many-to-one blogCategory
            'data.relationships.blogCategory'           => ['sometimes','required','array'],
            'data.relationships.blogCategory.data'      => ['nullable','array'],
            'data.relationships.blogCategory.data.type' => [
                'required_with:data.relationships.blogCategory.data.id','nullable','string','in:' . BlogCategory::TYPE_RESOURCE
            ],
            'data.relationships.blogCategory.data.id' => [
                'required_with:data.relationships.blogCategory.data.type','nullable','integer','exists:blog_categories,id'
            ]
        ];
    }
}
