<?php

namespace App\Http\Requests\Blog\BlogPost;

use Domain\Blog\Models\BlogCategory;
use Domain\Blog\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;

class BlogPostStoreRequest extends FormRequest
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
            'data.attributes.blog_category_id'    => ['required','integer'],
            'data.attributes.title'               => ['required','string'],
            'data.attributes.image_id'            => ['required','string'],
            'data.attributes.preview_id'          => ['required','string'],
            'data.attributes.content'             => ['required','string'],
            'data.attributes.status'              => ['required','string'],
            'data.attributes.published_at'        => ['required','date'],
            'data.attributes.description'         => ['sometimes','string'],
            'data.attributes.active'              => ['required','boolean'],
            'data.attributes.is_main'             => ['required','boolean'],
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
