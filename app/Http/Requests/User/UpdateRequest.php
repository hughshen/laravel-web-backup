<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'username' => [
                'bail',
                'required',
                Rule::unique('user')->ignore($this->route('user'))
            ],
        ];
    }

    // public function messages() {}
}
