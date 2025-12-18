<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_field' => ['required', 'string', 'max:100'], 
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'login_field.required' => 'Email/Nomor HP wajib diisi.',
            'login_field.string' => 'Input harus berupa teks.',
            'login_field.max' => 'Input terlalu panjang (maksimal 100 karakter).',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Tentukan apakah user login pakai Email atau Nomor Telepon
        $inputType = filter_var($this->input('login_field'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nomor_telepon';

        // 2. CEK STATUS AKTIF (Whitelisting Logic)
        // Cari user berdasarkan input (email atau no hp)
        $user = User::where($inputType, $this->input('login_field'))->first();

        // Jika user ketemu DAN statusnya Nonaktif (0)
        if ($user && $user->is_active == 0) {
            // Lempar error, hentikan proses login
            throw ValidationException::withMessages([
                'login_field' => 'Maaf, akun Anda telah dinonaktifkan oleh Pengurus RT.',
            ]);
        }

        // 3. COBA LOGIN (Auth::attempt)
        // Kita masukkan array kredensial dinamis (email/nomor_telepon + password)
        if (! Auth::attempt([$inputType => $this->input('login_field'), 'password' => $this->input('password')], $this->boolean('remember'))) {
            
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login_field' => trans('auth.failed'), // Pesan: Email/Password salah
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Fungsi pendukung untuk Rate Limiting (Mencegah spam login)
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_field' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Key untuk Rate Limiter
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login_field')).'|'.$this->ip());
    }
}
