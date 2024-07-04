<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // Change to your authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'title.string' => 'The event title must be a string.',
            'title.max' => 'The event title may not be greater than 255 characters.',
            'description.required' => 'The event description is required.',
            'description.string' => 'The event description must be a string.',
            'date.required' => 'The event date is required.',
            'date.date' => 'The event date must be a valid date.',
            'location.required' => 'The event location is required.',
            'location.string' => 'The event location must be a string.',
            'location.max' => 'The event location may not be greater than 255 characters.',
        ];
    }
}
