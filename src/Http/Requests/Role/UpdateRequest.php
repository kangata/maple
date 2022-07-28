<?php

namespace QuetzalStudio\Maple\Http\Requests\Role;

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
        return [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($this->route('id')),
            ],
            'guard_name' => 'required|in:web',
            'permissions' => 'nullable|array',
        ];
    }
}
