<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        //dd($this->input('password'));

        /* $users = DB::table('users')
            ->join('room_users', 'users.id', '=', 'room_users.user_id')
            ->join('rooms', 'room_users.room_id', '=', 'rooms.id')
            ->where('users.email', $this->input('email'))
            ->where('rooms.name', 'ROOM_911')
            ->select('users.*', 'room_users.room_id')
            ->get();
            //dd(count($users));
        if ( !count($users)) {
            
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email no tiene permisos',
            ]);
        }  */
        //, 'active' => $this->active
        //dd(Auth::attempt(['email' =>$this->email, 'password' => $this->password, 'active' => 1 ]));
        if ( !Auth::attempt(['email' =>$this->email, 'password' => $this->password, 'active' => 1 ], $this->boolean('remember') ))
        {
            $users = DB::table('users')
            ->where('users.email', $this->input('email'))
            ->update([
                'total_access' => DB::raw('total_access + 1'),
            ]);
            $user = DB::table('users')
            ->where('email', $this->input('email'))
            ->first();
            if($user) {
                DB::table('access')->insert([
                    'user_id' => $user->id,
                ]);
            }
            
            
            RateLimiter::hit($this->throttleKey());
            session()->flash('status', 'User no enabled');
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        //!Auth::attempt($this->only('email', 'password', 'active'), $this->boolean('remember'))

        $users = DB::table('users')
            ->where('users.email', $this->input('email'))
            ->update([
                'total_access' => DB::raw('total_access + 1'),
            ]);

        if ($users > 0) {

            $user = DB::table('users')
            ->where('email', $this->input('email'))
            ->first();

            DB::table('access')->insert([
                'user_id' => $user->id,
            ]);
        }
        //DB::raw('users.id')
        RateLimiter::clear($this->throttleKey());
        
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
