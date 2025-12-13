<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequestForm extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $itemId = $this->route('item');
        return [
            'id' => 'sometimes|required|string|unique:items,id,' . $itemId,
            'user_id' => 'sometimes|required|exists:users,id',
            'type_id' => 'sometimes|required|exists:types,id',
            'maintenance_unit_id' => 'sometimes|required|exists:users,id',
            'code' => 'sometimes|required|string|unique:items,code,' . $itemId . ',id',
            'order_number' => 'sometimes|required|integer|unique:items,order_number,' . $itemId . ',id',
            'name' => 'sometimes|required|string|max:255',
            'cost' => 'sometimes|required|integer',
            'acquisition_date' => 'sometimes|required|date',
            'acquisition_year' => 'sometimes|required|integer',
        ];
    }
}
