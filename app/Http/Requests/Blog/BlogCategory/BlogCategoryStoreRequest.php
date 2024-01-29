<?php

namespace App\Http\Requests\Blog\BlogCategory;

use Domain\Blog\Models\BlogCategory;
use Domain\Blog\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryStoreRequest extends FormRequest
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
            'data.type'                           => ['required','string','in:' . BlogCategory::TYPE_RESOURCE],
            'data.attributes'                     => ['required','array'],
            'data.attributes.name'                => ['required','string'],
            'data.attributes.description'         => ['sometimes','string'],
            'data.attributes.active'              => ['required','boolean'],
            // relationships
            'data.relationships'                       => ['sometimes','required','array'],
            // one to many blogPosts
            'data.relationships.blogPosts'             => ['sometimes','required','array'],
            'data.relationships.blogPosts.data'        => ['sometimes','required','array'],
            'data.relationships.blogPosts.data.*'      => ['sometimes','required','array'],
            'data.relationships.blogPosts.data.*.type' => ['present','string','in:' . BlogPost::TYPE_RESOURCE],
            'data.relationships.blogPosts.data.*.id'   => ['present','string', 'distinct', 'exists:blog_posts,id'],
            // one to many children
            'data.relationships.children'             => ['sometimes','required','array'],
            'data.relationships.children.data'        => ['sometimes','required','array'],
            'data.relationships.children.data.*'      => ['sometimes','required','array'],
            'data.relationships.children.data.*.type' => ['present','string','in:' . BlogCategory::TYPE_RESOURCE],
            'data.relationships.children.data.*.id'   => ['present','string', 'distinct', 'exists:blog_posts,id'],
            // many-to-one parent
            'data.relationships.parent'           => ['sometimes','required','array'],
            'data.relationships.parent.data'      => ['nullable','array'],
            'data.relationships.parent.data.type' => [
                'required_with:data.relationships.parent.data.id','nullable','string','in:' . BlogCategory::TYPE_RESOURCE
            ],
            'data.relationships.parent.data.id' => [
                'required_with:data.relationships.parent.data.type','nullable','integer','exists:blog_categories,id'
            ]
        ];
    }
}
