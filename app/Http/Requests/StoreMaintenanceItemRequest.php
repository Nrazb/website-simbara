<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'item_id' => 'nullable|exists:items,id',
            'item_status' => 'nullable|in:good,damaged,lost,repaired',
            'information' => 'nullable|string',
            'request_status' => 'required|in:pending,approved,rejected',
            'unit_confirmed' => 'boolean',
        ];
    }
}
