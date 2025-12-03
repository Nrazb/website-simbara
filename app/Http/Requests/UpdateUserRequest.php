<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user');
        return [
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|unique:users,code,' . $userId,
            'password'    => 'nullable|string|min:6',
            'role'        => 'required|in:ADMIN,UNIT,MAINTENANCE_UNIT',
            'can_borrow'  => 'required|boolean',
    ];
    }
}
