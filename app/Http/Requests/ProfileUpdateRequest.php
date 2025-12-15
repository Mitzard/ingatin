<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        $userId = Auth::id();
        return [
            'profile_photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'digits:16', Rule::unique('users', 'nik')->ignore($userId)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'nomor_telepon' => ['required', 'string', 'max:15', Rule::unique('users', 'nomor_telepon')->ignore($userId)],

            'alamat' => ['nullable', 'string', 'max:500'],
            'usia' => ['nullable', 'integer', 'min:15'],
            'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
