<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMutationItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'maintenance_unit_id' => 'required|exists:users,id',
            'item_id' => 'nullable|exists:items,id',
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'unit_confirmed' => 'boolean',
            'recipient_confirmed' => 'boolean',
        ];
    }
}
