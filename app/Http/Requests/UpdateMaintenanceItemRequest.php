<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceItemRequest extends FormRequest
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
            'item_status' => 'sometimes|nullable|in:good,damaged,lost,repaired',
            'information' => 'sometimes|nullable|string',
            'request_status' => 'sometimes|required|in:pending,approved,rejected',
            'unit_confirmed' => 'boolean',
        ];
    }
}
