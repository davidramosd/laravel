<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VerificationCode extends Controller
{
    public function create(): View
    {
        return view('auth.identification');
    }


    public function store(Request $request, User $user)
    {
        // User::where('code', $request->input('code'))
        // ->update(['active' => true,]);
    
        $code = $request->input('code');
        $user_permission = DB::table('users')
        ->join('room_users', 'users.id', '=', 'room_users.user_id')
        ->join('rooms', 'room_users.room_id', '=', 'rooms.id')
        ->where('users.code', $code)
        ->where('users.active', 1)
        ->where('rooms.name', 'ROOM_911')
        ->select('users.*', 'room_users.room_id')
        ->first();
        //->get();
        //dd(count($users));
        
        //$user = User::where('code', $code)->first();
        $users = User::where('code', $code)->first();

        if (!$user_permission && $users) {
            DB::table('users')
            ->where('users.code',$code)
            ->update([
                'total_access' => DB::raw('total_access + 1'),
            ]);
            DB::table('access')->insert([
                'user_id' => $users->id,
            ]);
        }
        
        if ($user_permission) {
            DB::table('users')
            ->where('users.email', $user_permission->email ?? '')
            ->update([
                'total_access' => DB::raw('total_access + 1'),
            ]);
            DB::table('access')->insert([
                'user_id' => $user_permission->id,
            ]);
            $request->session()->put('auth.verification', time());
            $request->session()->put(['code' => $code]);
            return redirect()->route('employee', ['code' => $code]);
        }else {
            session()->flash('status', 'Code not found or disabled user');
            return redirect()->route('identification');
        }
    }

}
