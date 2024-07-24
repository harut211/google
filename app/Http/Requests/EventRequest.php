<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'string|nullable',
            'start' => 'required|date',
            'end' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Title must be a string',
            'title.required' => 'Title is required',
            'description.string' => 'Description must be a string',
            'description.required' => 'Description is required',
            'start.date' => 'Start date must be a date',
            'start.required' => 'Start date is required',
            'end.date' => 'End date must be a date',
            'end.required' => 'End date is required',
        ];
    }
}
