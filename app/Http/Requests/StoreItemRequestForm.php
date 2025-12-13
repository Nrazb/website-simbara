<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreItemRequestForm extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::user()->id,
        ]);
    }

    public function rules()
    {
        $itemRequest = $this->route('itemRequest');
        $maxQty = $itemRequest ? (int) $itemRequest->qty : null;

        return [
            'id' => 'sometimes|string|unique:items,id',
            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:types,id',
            'maintenance_unit_id' => 'required|exists:users,id',
            'code' => 'required|string',
            'quantity' => 'required|integer|min:1' . ($maxQty !== null ? '|max:' . $maxQty : ''),
            'name' => 'required|string|max:255',
            'cost' => 'required|integer',
            'acquisition_date' => 'required|date',
            'acquisition_year' => 'required|integer',
        ];
    }
}
