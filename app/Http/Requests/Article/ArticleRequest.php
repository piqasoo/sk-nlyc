<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sort'      => 'nullable|string|in:view_count,comment_count,created_at',
            'order'     => 'nullable|string|in:asc,desc',
            'paginate'  => 'nullable|boolean',
            'limit'     => 'nullable|numeric',
            'page'      => 'nullable|numeric',
        ];
    }
}
