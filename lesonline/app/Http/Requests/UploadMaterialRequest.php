<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMaterialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'transaction_id' => 'nullable|exists:transactions,id',
        ];
    }
}
