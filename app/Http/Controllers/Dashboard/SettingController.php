<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Seller Store Settings
 */
class SettingController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('dashboard.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'store_name'        => 'required|string|max:255',
            'store_description' => 'nullable|string|max:1000',
            'store_logo'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'phone'             => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'city'              => 'nullable|string|max:100',
            'province'          => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('store_logo')) {
            if ($user->store_logo) {
                Storage::disk('public')->delete($user->store_logo);
            }
            $validated['store_logo'] = $request->file('store_logo')->store('stores', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}
