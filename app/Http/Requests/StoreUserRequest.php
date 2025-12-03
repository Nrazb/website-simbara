<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:users,code',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,UNIT,MAINTENANCE_UNIT',
            'can_borrow' => 'required|boolean',
        ];
    }
}
