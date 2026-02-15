<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use App\Models\RememberedDevice;

class CustomTwoFactorController extends Controller
{
  public function store(Request $request, TwoFactorAuthenticationProvider $provider)
  {
    $request->validate([
      'code' => 'required|string',
    ]);

    $user = Auth::user();

    if (!$provider->verify($user->two_factor_secret, $request->code)) {
      return back()->withErrors(['code' => 'The provided 2FA code is invalid.']);
    }

    if ($request->has('remember_device')) {
      $token = Str::random(64);
      $hashedToken = hash('sha256', $token);

      $user->rememberedDevices()->create([
        'token' => $hashedToken,
        'expires_at' => now()->addDays(15),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
      ]);

      Cookie::queue('remember_2fa', $token, 60 * 24 * 15); // 15 days
    }

    app(ConfirmTwoFactorAuthentication::class)($user);

    return redirect()->intended(config('fortify.home'));
  }
}
