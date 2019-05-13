<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'username' => [
                'bail',
                'required',
                'unique:user,username',
            ],
            'password' => ['required'],
        ];
    }

    // public function messages() {}
}
