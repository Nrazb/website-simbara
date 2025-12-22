<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class ImportItemsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,csv,xls',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = 'File yang diunggah tidak valid. Pastikan file berekstensi .xlsx, .xls, atau .csv.';

        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => $message,
                'errors' => $validator->errors(),
            ], 422));
        }

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $message)
        );
    }
}

