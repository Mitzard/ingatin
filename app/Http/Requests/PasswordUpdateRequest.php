<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
            'current_password' => ['required', 'string'], 
            
            // Kata Sandi Baru
            'password' => [
                'required', 
                'string', 
                'confirmed', 
                
                Password::min(8)
                    ->letters()        
                    ->mixedCase()      
                    ->numbers()
            ],
            
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Kata sandi lama wajib diisi.',
            
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',

            'password.mixed_case' => 'Kata sandi baru harus mengandung huruf besar dan kecil.',
            'password.letters' => 'Kata sandi baru harus mengandung minimal satu huruf.',
            'password.numbers' => 'Kata sandi baru harus mengandung minimal satu angka.',
        ];
    }
}
