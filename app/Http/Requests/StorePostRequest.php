<?php

namespace App\Http\Requests;

// use Illuminate\Contracts\Validation\ValidationRule;

use App\Rules\IntegerArray;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'user_ids' => [
                'required',
                'array',
                new IntegerArray(),
                // function ($attribute, $value, $fail) {
                //     $integerOnly = collect($value)->every(fn($element) => is_int($element));
                //     if (!$integerOnly) {
                //         $fail($attribute . ' can only be integer');
                //     }
                // }
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Please enter a value for body.',
            'title.string' => 'Title value must be a string'
        ];
    }
}
