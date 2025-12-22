<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMaintenanceItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::user()->id,
        ]);
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'maintenance_user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'item_status' => 'nullable|in:PENDING,GOOD,DAMAGED,REPAIRED',
            'information' => 'nullable|string',
        ];
    }
}
