<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'type_id' => 'sometimes|required|exists:types,id',
            'name' => 'sometimes|required|string|max:255',
            'detail' => 'sometimes|required|string',
            'qty' => 'sometimes|required|integer|min:1',
            'reason' => 'sometimes|required|string',
        ];
    }
}
