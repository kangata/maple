<?php

namespace QuetzalStudio\Maple\Http\Requests\Role;

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
            'name' => 'required|unique:roles',
            'guard_name' => 'required|in:web',
            'permissions' => 'nullable|array',
        ];
    }
}
