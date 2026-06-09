<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect('/');
        }

        $user = User::findOrFail(Auth::id());

        // Must be marked verified
        if (! $user->is_verified) {
            Auth::logout();
            return redirect('/error?error=403');
        }

        // Super admin may access without bank association
        if ($user->role == 'Superadmin') {
            return $next($request);
        }

        // Ensure user is associated with an approved bank
        $userBank = $user->blood_bank;
        if (! $userBank) {
            Auth::logout();
            abort(403, 'User is not associated with any blood bank.');
        }

        // set current tenant bank in session
        session(['bank_id' => $userBank->bank_id]);

        return $next($request);
    }
}
