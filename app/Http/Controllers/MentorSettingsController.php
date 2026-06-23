<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorSettingsController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        $mentor = auth()->user()->mentor;

        if (! $mentor) {
            return back()->with('error', 'Data mentor tidak ditemukan.');
        }

        return view('mentor.settings', compact('mentor'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tarif_per_jam' => 'required|numeric|min:0',
            'keahlian' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'link_meeting' => 'nullable|url',
        ]);

        $mentor = auth()->user()->mentor;

        if (! $mentor) {
            return back()->with('error', 'Data mentor tidak ditemukan.');
        }

        $mentor->update($validated);

        return back()->with('success', 'Data mentor berhasil diperbarui!');
    }
}
