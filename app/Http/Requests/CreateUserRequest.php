<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required',"regex:/\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/"],
            'email' => ['required','email','unique:users'],
            'password' => ['required','min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
