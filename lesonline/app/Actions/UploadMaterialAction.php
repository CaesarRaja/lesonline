<?php

namespace App\Actions;

use App\Models\Material;
use App\Models\Mentor;
use Illuminate\Http\UploadedFile;

class UploadMaterialAction
{
    public function execute(Mentor $mentor, array $data, UploadedFile $file): Material
    {
        $path = $file->store('materials', 'public');

        return Material::create([
            'mentor_id' => $mentor->id,
            'transaction_id' => $data['transaction_id'] ?? null,
            'judul' => $data['judul'],
            'file_path' => $path,
            'tipe' => $file->getClientOriginalExtension(),
        ]);
    }
}
