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
            'item_id' => 'sometimes|required|exists:items,id',
            'item_status' => 'sometimes|nullable|in:GOOD,DAMAGED,REPAIRED',
            'information' => 'sometimes|nullable|string',
            'request_status' => 'sometimes|required|in:PENDING,PROCESS,COMPLETED,REJECTED,REMOVED',
            'unit_confirmed' => 'boolean',
        ];
    }
}
