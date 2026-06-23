<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function download(User $user, Material $material): bool
    {
        if ($material->mentor_id === $user->mentor?->id) {
            return true;
        }

        if ($material->transaction_id === null) {
            return true;
        }

        return $user->studentTransactions()
            ->where('status_pembayaran', 'success')
            ->whereHas('materials', fn ($q) => $q->where('id', $material->id))
            ->exists();
    }

    public function update(User $user, Material $material): bool
    {
        return $material->mentor_id === $user->mentor?->id;
    }

    public function delete(User $user, Material $material): bool
    {
        return $material->mentor_id === $user->mentor?->id;
    }
}
