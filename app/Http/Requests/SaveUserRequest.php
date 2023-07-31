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
            //$user = $this->route('users.show');
            $user = $this->request->get('id');
            return [
                'name' => ['string', 'max:255'],
                'lastname' => ['string', 'max:255'],
                'document' => ['numeric','min_digits:3', 'max_digits:5'],
                //'code' => [ 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
                'code' => [ 'numeric', Rule::unique(User::class)->ignore($user)],
                'department_id' => ['required','numeric'],
                'active' => ['boolean'],
                //Rule::unique('users')->ignore($user->id),
                //'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                //'email' => ['string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
                'email' => [ 'required','string','email', 'max:255', Rule::unique(User::class)->ignore($user)],
                //'email' => ['string', 'email', Rule::unique('users')],
                'password' => [Password::defaults()],
            ];
        }
        return [
            'name' => ['required','string', 'max:255'],
            'lastname' => ['required','string', 'max:255'],
            'document' => ['numeric','min_digits:3', 'max_digits:5'],
            //'code' => [ 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'code' => ['required', 'numeric', Rule::unique(User::class)],
            'department_id' => ['required','numeric'],
            'type_user_id' => ['required','numeric',  Rule::in(['1'])],
            'active' => ['boolean'],
            //Rule::unique('users')->ignore($user->id),
            //'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            //'email' => ['string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
            'email' => [ 'required','string','email', 'max:255', Rule::unique(User::class)],
            //'email' => ['string', 'email', Rule::unique('users')],
            'password' => ['required', Password::defaults()],
        ];
    }
}
