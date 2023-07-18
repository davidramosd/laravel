<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class VerificationCode extends Controller
{
    public function create(): View
    {
        return view('auth.identification');
    }


    public function store(Request $request, User $user)
    {
        // //dd($request);
        // User::where('code', $request->input('code'))
        // ->update(['active' => true,]);
        
        // return redirect('/login');
        $document = $request->input('code');

        // Buscar al usuario por el documento
        $user = User::where('code', $document)->first();

        if ($user) {
            $request->session()->put('auth.verification', time());
            // El usuario existe, redirigir a la pantalla de inicio de sesión
            return redirect()->route('login');
        }else {
            return redirect()->route('identification');
        }
    }

}
