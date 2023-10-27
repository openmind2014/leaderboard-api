<?php
/**
 * Author: Jun Chen
 */

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:users|min:2|max:255|regex:/^[a-zA-Z\s\pL\pM.]+$/u', // Only letters and spaces plus unicode support
            'age' => 'required|integer|min:16|max:100',
            'address' => 'required|string|min:2|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The name is already taken.',
            'name.regex' => 'The name is not in a valid format. Please use letters and spaces.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name may not be greater than :max characters.',
            'age.required' => 'The age field is required.',
            'age.integer' => 'The age must be an integer.',
            'age.min' => 'The age must be at least :min.',
            'age.max' => 'The age may not be greater than :max.',
            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.min' => 'The address must be at least :min characters.',
            'address.max' => 'The address may not be greater than :max characters.',
        ];
    }
}
