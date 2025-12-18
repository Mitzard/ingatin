<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan view ini ada
    }

    public function login(LoginRequest $request)
    {
        // 1. Tentukan jenis kredensial yang digunakan user (Email/Nomor HP/NIK)
        $loginField = $request->input('login_field');
        $fieldType = $this->findLoginFieldType($loginField);

        $credentials = [
            $fieldType => $loginField,
            'password' => $request->password
        ];

        // 2. Coba Autentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 3. Logika Redirection Berbasis Peran dan Niat (Intended URL)
            // intended() akan mengarahkan ke halaman terproteksi yang terakhir diakses user
            
            if ($user->isPengurus()) {
                // REDIRECT PENGURUS: Selalu ke Dashboard Khusus (Kelola Kegiatan)
                return redirect()->route('dashboard'); 
            } 
            
            // REDIRECT WARGA: Ikuti intended URL atau ke Beranda
            // Jika ada intended URL (datang dari Kalender/Daftar/Arsip), redirect ke sana.
            // Jika tidak ada (datang dari klik tombol Login), redirect ke Beranda.
            return redirect()->intended(route('home')); 
        }

        // 4. Gagal
        return back()->withErrors(['login_field' => 'Cek Kembali Atau Mungkin Kamu Bukan Warga RT 19']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman Beranda setelah logout
        return redirect()->route('home');
    }

    protected function findLoginFieldType(string $loginField): string
    {
        // Prioritas 1: Email (Cek format)
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            return 'email'; 
        } 
        
        // if (is_numeric($loginField) && strlen($loginField) === 16) {
        //     return 'nik'; 
        // }
        
        // Prioritas 3: Default ke Nomor Telepon
        return 'nomor_telepon'; 
    }
}
