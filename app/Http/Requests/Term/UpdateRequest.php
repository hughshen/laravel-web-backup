<?php

namespace App\Http\Requests\Term;

use App\Models\Term;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'taxonomy' => ['bail', 'required', Rule::in(Term::TAX_LIST)],
            'slug' => [
                'bail',
                'required',
                Rule::unique('term')->ignore($this->route('term'))
            ],
            'status' => ['bail', 'required', Rule::in([0, 1])],
        ];
    }

    // public function messages() {}
}
