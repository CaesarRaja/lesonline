<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveWithdrawalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'alasan_penolakan' => 'nullable|required_if:status,rejected|string|max:500',
        ];
    }
}
