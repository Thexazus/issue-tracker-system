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
            'screenshot' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
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
            'screenshot.image' => 'The screenshot file must be an image.',
            'screenshot.mimes' => 'The screenshot format must be jpeg, png, jpg, or gif.',
            'screenshot.max' => 'The screenshot size may not exceed 2MB.',
        ];
    }
}
