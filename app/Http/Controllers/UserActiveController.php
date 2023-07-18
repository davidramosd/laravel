<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserActiveController extends Controller
{
    public function update(Request $request, User $user) {
        //dd($user);
        $user->active = !$user->active;
        $user->save();
        //$post->update($validated);
 
         //$user->update($request->validated());
 
      
         return to_route('dashboard')->with('status', 'User Updata!');
     }
}
