<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'jumlah' => 'required|numeric|min:50000',
            'bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:255',
        ];
    }
}
