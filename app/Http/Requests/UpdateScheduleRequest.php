<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'start'       => 'required|date',
            'start_time'  => 'required', // <--- Pastikan ini ada
            'end'         => 'nullable|date|after_or_equal:start',
            'end_time'    => 'nullable', // <--- Pastikan ini ada
            'lokasi'      => 'required|string',
            'description' => 'nullable|string',
            'image_flyer_path' => ['nullable', 'image', 'max:2048']
        ];
    }
}
