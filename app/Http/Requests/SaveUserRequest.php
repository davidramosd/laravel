<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


class SaveUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if($this->isMethod('PATCH')) {
            return [
                'name' => ['string', 'max:255'],
                'lastname' => ['string', 'max:255'],
                'document' => ['string', 'max:255'],
                'code' => [ 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
                'department_id' => ['numeric'],
                'active' => ['boolean'],
                //Rule::unique('users')->ignore($user->id),
                //'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                //'email' => ['string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
                'email' => [ 'string','email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
                //'email' => ['string', 'email', Rule::unique('users')],
                'password' => [Password::defaults()],
            ];
        }
        return [
            'name' => ['required', 'string', 'max:255'],
           /*  'lastname' => ['required', 'string', 'max:255'],
            'document' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'departament_id' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()], */
        ];
    }
}
