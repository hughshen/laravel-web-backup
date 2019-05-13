<?php

namespace App\Http\Requests\Term;

use App\Models\Term;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'taxonomy' => ['bail', 'required', Rule::in(Term::TAX_LIST)],
            'slug' => ['bail', 'required', 'unique:term,slug'],
            'status' => ['bail', 'required', Rule::in([0, 1])],
        ];
    }

    // public function messages() {}
}
