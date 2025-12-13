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
            'item_id' => 'required|exists:items,id',
            'item_status' => 'nullable|in:GOOD,DAMAGED,REPAIRED',
            'information' => 'nullable|string',
            'request_status' => 'required|in:PENDING,PROCESS,COMPLETED,REJECTED,REMOVED',
            'unit_confirmed' => 'boolean',
        ];
    }
}
