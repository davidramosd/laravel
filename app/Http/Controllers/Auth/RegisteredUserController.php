<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $type_users = DB::table('type_users')->where('type_users.name', 'admin_room_911')->get();
        return view('auth.register', [ 'type_users' => $type_users]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $type_users = DB::table('type_users')->where('type_users.name', 'admin_room_911')->get();
        //dd($type_users[0]->id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'numeric','min_digits:3', 'max_digits:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type_user_id' => ['required','numeric',  Rule::in([$type_users[0]->id])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'code' => $request->code,
            'type_user_id' => $request->type_user_id,
        ]);

        /* DB::table('room_users')->insert([
            'user_id' => $user->id,
            'room_id' => 1
        ]); */

        event(new Registered($user));

        //Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('status', 'User Admin Created!');;
    }
}
