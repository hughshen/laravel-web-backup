<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'title' => ['required'],
            'content' => ['required'],
            'slug' => ['bail', 'required', 'unique:post,slug'],
            'status' => ['bail', 'required', Rule::in(Post::STATUS_LIST)],
        ];
    }

    // public function messages() {}
}
