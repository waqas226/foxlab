<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Force2FASetup
{
  public function handle(Request $request, Closure $next)
  {
    $user = Auth::user();

    if ($user && is_null($user->two_factor_secret)) {
      // Auto generate secret
      $google2fa = app('pragmarx.google2fa');
      $user
        ->forceFill([
          'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
        ])
        ->save();

      return redirect()->route('2fa.setup');
    }

    return $next($request);
  }
}
