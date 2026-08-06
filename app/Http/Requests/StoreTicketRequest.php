<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only QA and Admin can report/create tickets
        return auth()->user()->isQA() || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Max 2MB image
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tiket wajib diisi.',
            'title.max' => 'Judul tiket tidak boleh lebih dari 255 karakter.',
            'description.required' => 'Deskripsi tiket wajib diisi.',
            'priority.required' => 'Prioritas tiket wajib dipilih.',
            'priority.in' => 'Prioritas yang dipilih tidak valid.',
            'screenshot.image' => 'File screenshot harus berupa gambar.',
            'screenshot.mimes' => 'Format screenshot harus jpeg, png, jpg, atau gif.',
            'screenshot.max' => 'Ukuran screenshot tidak boleh lebih dari 2MB.',
        ];
    }
}
