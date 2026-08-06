<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isQA()) {
            // QA can only edit tickets they reported
            return $ticket->reporter_id === $user->id;
        }

        if ($user->isDeveloper()) {
            // Developers can only update tickets assigned to them
            return $ticket->assigned_to_id === $user->id;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = auth()->user();

        // If Developer, they can only change status
        if ($user->isDeveloper()) {
            return [
                'status' => ['required', 'in:open,in_progress,resolved,closed'],
            ];
        }

        // If Admin or QA
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // Only Admin can assign/reassign tickets
        if ($user->isAdmin()) {
            $rules['assigned_to_id'] = ['nullable', 'exists:users,id'];
        }

        return $rules;
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
            'status.required' => 'Status tiket wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'screenshot.image' => 'File screenshot harus berupa gambar.',
            'screenshot.mimes' => 'Format screenshot harus jpeg, png, jpg, atau gif.',
            'screenshot.max' => 'Ukuran screenshot tidak boleh lebih dari 2MB.',
            'assigned_to_id.exists' => 'Developer yang ditunjuk tidak valid.',
        ];
    }
}
