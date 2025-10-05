<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequestForm extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|string|unique:items,id',
            'user_id' => 'required|exists:users,id',
            'type_id' => 'nullable|exists:types,id',
            'maintenance_unit_id' => 'nullable|exists:users,id',
            'code' => 'required|string|unique:items,code',
            'order_number' => 'required|integer|unique:items,order_number',
            'name' => 'required|string|max:255',
            'cost' => 'required|integer',
            'acquisition_date' => 'required|date',
            'acquisition_year' => 'required|integer',
        ];
    }
}
