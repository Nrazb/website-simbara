<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRemoveItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'item_id' => 'sometimes|nullable|exists:items,id',
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'unit_confirmed' => 'boolean',
        ];
    }
}
