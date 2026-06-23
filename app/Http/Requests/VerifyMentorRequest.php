<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyMentorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:verified,rejected',
            'alasan' => 'nullable|string|max:500',
        ];
    }
}
