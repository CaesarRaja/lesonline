<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:student,mentor,admin',
            'verification_status' => 'nullable|in:pending,verified,rejected',
            'keahlian' => 'nullable|string|max:255',
            'tarif_per_jam' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'link_meeting' => 'nullable|url',
        ];
    }
}
