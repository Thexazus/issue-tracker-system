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
            return $ticket->reporter_id === $user->id;
        }

        if ($user->isDeveloper()) {
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

        if ($user->isDeveloper()) {
            return [
                'status' => ['required', 'in:open,in_progress,resolved,closed'],
            ];
        }

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

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
            'title.required' => 'Ticket title is required.',
            'title.max' => 'Ticket title may not exceed 255 characters.',
            'description.required' => 'Ticket description is required.',
            'priority.required' => 'Ticket priority is required.',
            'priority.in' => 'Selected priority is invalid.',
            'status.required' => 'Ticket status is required.',
            'status.in' => 'Selected status is invalid.',
            'screenshot.image' => 'The screenshot file must be an image.',
            'screenshot.mimes' => 'The screenshot format must be jpeg, png, jpg, or gif.',
            'screenshot.max' => 'The screenshot size may not exceed 2MB.',
            'assigned_to_id.exists' => 'Selected developer is invalid.',
        ];
    }
}
