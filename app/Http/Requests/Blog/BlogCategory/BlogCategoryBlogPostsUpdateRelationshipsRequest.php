<?php

namespace App\Http\Requests\Blog\BlogCategory;

use Domain\Blog\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryBlogPostsUpdateRelationshipsRequest extends FormRequest
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
            'data.*.id'   => ['required','string','exists:blog_posts,id'],
            'data.*.type' => ['required','string','in:' . BlogPost::TYPE_RESOURCE],
        ];
    }
}
