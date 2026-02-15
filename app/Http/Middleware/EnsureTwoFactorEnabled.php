<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorEnabled
{
  public function handle(Request $request, Closure $next)
  {
    $user = Auth::user();

    if ($user && is_null($user->two_factor_secret)) {
      // Prevent loop if already on 2FA enable route
      if (!$request->is('2fa/enable')) {
        return redirect()->route('2fa.enable');
      }
    }

    return $next($request);
  }
}
