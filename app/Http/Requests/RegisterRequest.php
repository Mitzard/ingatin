<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nomor_telepon' => ['required', 'string', 'min:10', 'max:13', 'regex:/^08[0-9]{10,13}$/', 'unique:users,nomor_telepon'],
            'nik' => ['required', 'numeric', 'digits:16', 'unique:users,nik'], // Wajib 16 digit dan unik

            'password' => [
                'required',
                'string',
                'confirmed',

                // --- ATURAN BARU ---
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    // ->symbols()
                    // ->uncompromised(),

                // 'different:nama_lengkap',
                // 'different:email',
                // 'different:nik',
            ],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            // Nama Lengkap
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',

            // Email
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid (contoh: user@domain.com).',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.',

            // Nomor Telepon
            'nomor_telepon.required' => 'Nomor HP wajib diisi.',
            'nomor_telepon.min' => 'Nomor HP minimal 9 digit.',
            'nomor_telepon.max' => 'Nomor HP maksimal 13 digit.',
            'nomor_telepon.regex' => 'Format Nomor HP tidak valid. Contoh: 081234xxxxxx.',
            'nomor_telepon.unique' => 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.',

            // NIK
            'nik.required' => 'NIK (Nomor Induk Kependudukan) wajib diisi.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',

            // Password
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi yang dimasukkan.',
            'password.mixed_case' => 'Kata sandi harus mengandung kombinasi huruf besar dan kecil.',
            'password.letters' => 'Kata sandi harus mengandung minimal satu huruf.',
            'password.numbers' => 'Kata sandi harus mengandung minimal satu angka.',
            // 'password.symbols' => 'Kata sandi harus mengandung minimal satu simbol.',
            'password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.',
            // 'password.different' => 'Kata sandi tidak boleh sama dengan Nama Lengkap, Email, atau NIK Anda.',
        ];
    }
}
