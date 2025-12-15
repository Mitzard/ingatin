<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;

class WargaController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        
        // Pastikan Anda membuat file view ini: resources/views/user/profile.blade.php
        return view('warga.profile', compact('user'));
    }

    public function settings()
    {
        // Memanggil view di resources/views/warga/settings.blade.php
        return view('warga.pengaturan'); 
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        
        // --- 1. Upload Foto Profil ---
        if ($request->hasFile('profile_photo')) {
            $photoFile = $request->file('profile_photo');
            $path = $photoFile->store('profile-photos', 'public');

            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $path;
        }

        // --- 2. Update Data Diri ---
        $user->fill($validated);
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();
        
        // Validasi: Pastikan password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi lama yang Anda masukkan salah.']);
        }
        
        // Update Password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Kata sandi berhasil diperbarui!');
    }
}
