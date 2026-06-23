<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:50',
            'transaction_id' => 'required|exists:transactions,id',
        ];
    }
}
