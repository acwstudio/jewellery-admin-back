<?php

namespace App\Http\Requests\Blog\BlogCategory;

use Domain\Blog\Models\BlogCategory;
use Domain\Blog\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryUpdateRequest extends FormRequest
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
            'data.attributes.name'                => ['sometimes','string'],
            'data.attributes.description'         => ['sometimes','string'],
            'data.attributes.active'              => ['sometimes','boolean'],
            // relationships
            'data.relationships'                       => ['sometimes','required','array'],
            // one to many blogPosts
            'data.relationships.blogPosts'             => ['sometimes','required','array'],
            'data.relationships.blogPosts.data'        => ['sometimes','required','array'],
            'data.relationships.blogPosts.data.*'      => ['sometimes','required','array'],
            'data.relationships.blogPosts.data.*.type' => ['present','string','in:' . BlogPost::TYPE_RESOURCE],
            'data.relationships.blogPosts.data.*.id'   => ['present','string', 'distinct', 'exists:blog_posts,id'],
        ];
    }
}
