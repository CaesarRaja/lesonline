<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatformFeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'persentase' => 'required|numeric|min:0|max:100',
            'nominal_tetap' => 'required|numeric|min:0',
        ];
    }
}
