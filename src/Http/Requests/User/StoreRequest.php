<?php

namespace QuetzalStudio\Maple\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'same:password',
            'roles' => 'nullable|array',
        ];
    }
}
