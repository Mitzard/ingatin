<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Hanya user yang belum login (guest) yang bisa mengakses register
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register'); // Pastikan view ini ada
    }

    public function register(RegisterRequest $request)
    {
        // 1. Validasi sudah ditangani oleh RegisterRequest
        
        // 2. Buat User Baru
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'nik' => $request->nik,
            // Kolom demografi lain bersifat nullable saat ini
            'alamat' => $request->alamat,
            'usia' => $request->usia,
            'pekerjaan' => $request->pekerjaan,

            // Password harus di-hash sebelum disimpan
            'password' => Hash::make($request->password), 
            
            // Role default: WARGA
            'role' => 'warga', 
        ]);
        
        // 3. Redirect ke Halaman Login dengan pesan sukses
        // Sesuai alur Anda: setelah daftar, user diarahkan ke halaman login
        return redirect()->route('login')->with('success', 'Akun berhasil didaftarkan! Silakan masuk untuk melanjutkan.');
    }
}
