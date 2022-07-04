<?php

namespace QuetzalStudio\Maple\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->route('id')),
            ],
            'roles' => 'nullable|array',
        ];

        if ($this->filled('password')) {
            $rules['password'] = 'required|min:6';
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $rules;
    }
}
