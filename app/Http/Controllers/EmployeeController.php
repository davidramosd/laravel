<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index(Request $request) {
        //dd($request->code);
        $code = session('code');
        //dd($code);
        $userAccess = User::select('users.id', 'users.name', 'access.registered_at as date_access')
        ->join('access', 'users.id', '=', 'access.user_id')
        ->rightjoin('type_users', 'users.type_user_id', '=', 'type_users.id')
        ->where('users.code', $code)
        ->where('type_users.name', 'employee')
        ->paginate(10);
        //->get();
        return view('employee', ['user' => $userAccess ]);
    }
}
