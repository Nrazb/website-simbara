<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'qty' => 'required|integer|min:1',
            'reason' => 'required|string',
        ];
    }
}
