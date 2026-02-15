<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class TwoFactorMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle($request, Closure $next)
  {
    $user = auth()->user();

    if ($user && $user->two_factor_secret) {
      $cookieKey = '2fa_verified_' . $user->id;

      // If cookie or session says verified, skip OTP
      //   Cookie::queue(Cookie::forget($cookieKey));
      //   echo json_encode($request->cookie($cookieKey));
      //   return $request->cookie($cookieKey);
      if (!session('2fa_passed') && !$request->cookie($cookieKey)) {
        return redirect()->route('2fa.verify');
      }

      session(['2fa_passed' => true]); // Refresh session flag
    }

    return $next($request);
  }
}
