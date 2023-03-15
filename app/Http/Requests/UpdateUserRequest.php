<?php

namespace App\Http\Requests;

class UpdateUserRequest extends CreateUserRequest
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'email' => 'required|email|unique:users,email,' . $this->route()->parameter('user')
            ]
        );
    }
}
