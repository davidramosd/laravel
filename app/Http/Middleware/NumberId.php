<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NumberId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $redirectToRoute = null, $passwordTimeoutSeconds = null): Response
    {
       /*  if(User::where(email,  Auth::user()->email)->active && !Auth::check()){
            return  redirect('/identification');
        } */
        if ($this->shouldConfirmCode($request, $passwordTimeoutSeconds)) {
            if ($request->expectsJson()) {
                return $this->responseFactory->json([
                    'message' => 'Code confirmation required.',
                ], 423);
            }

            return redirect('/identification');
        }
        return $next($request);
    }

    protected function shouldConfirmCode($request, $passwordTimeoutSeconds = null, $passwordTimeout =10800)
    {
        $confirmedAt = time() - $request->session()->get('auth.verification', 0);

        return $confirmedAt > ($passwordTimeoutSeconds ?? $passwordTimeout);
    }
    
}
