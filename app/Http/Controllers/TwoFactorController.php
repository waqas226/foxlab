<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RobThree\Auth\TwoFactorAuth;
use Illuminate\Support\Facades\Auth;

use App\Models\SiteConstant;
use App\Models\User;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
  public function verifyForm()
  {
    return view('2fa.auth-two-steps-basic');
  }

  public function verify(Request $request)
  {
    $request->validate(['code' => 'required']);

    $google2fa = app('pragmarx.google2fa');
    $user = $request->user();

    $secret = Crypt::decrypt($user->two_factor_secret);

    if ($google2fa->verifyKey($secret, $request->code)) {
      $user
        ->forceFill([
          'two_factor_recovery_codes' => encrypt(
            json_encode(
              collect(range(1, 8))
                ->map(fn() => Str::random(10))
                ->all()
            )
          ),
        ])
        ->save();
      if ($request->remember_device) {
        return redirect()
          ->intended('dashboard')
          ->withCookie(
            cookie('2fa_verified_' . $user->id, true, 60 * 24 * 15) // 15 days in minutes
          );
      } else {
        //remove cookie
        session(['2fa_passed' => true]);
        cookie('2fa_verified_' . $user->id, false);
        return redirect()->intended('dashboard');
      }
      // Set cookie valid for 15 days
    }

    return back()->withErrors(['code' => 'Invalid code']);
  }

  public function show(Request $request)
  {
    $user = $request->user();
    $google2fa = app('pragmarx.google2fa');
    $siteConstant = SiteConstant::first();
    $secret = Crypt::decrypt($user->two_factor_secret);
    $company = $siteConstant->site_name;
    $qrCodeUrl = $google2fa->getQRCodeUrl($company, $user->email, $secret);

    $writer = new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd()));

    $qrCodeSvg = $writer->writeString($qrCodeUrl);

    $recoveryCodes = collect(range(1, 8))
      ->map(fn() => Str::random(10))
      ->toArray();

    return view('auth.2fa-setup', [
      'qrCodeSvg' => $qrCodeSvg,
      'secret' => $secret,
      'recoveryCodes' => $recoveryCodes,
    ]);
  }

  public function confirm(Request $request)
  {
    $request->validate([
      'code' => 'required',
    ]);

    $google2fa = app('pragmarx.google2fa');
    $user = $request->user();

    $secret = Crypt::decrypt($user->two_factor_secret);

    if ($google2fa->verifyKey($secret, $request->code)) {
      $user
        ->forceFill([
          'two_factor_recovery_codes' => encrypt(
            json_encode(
              collect(range(1, 8))
                ->map(fn() => Str::random(10))
                ->all()
            )
          ),
        ])
        ->save();
      if ($request->remember_device) {
        return redirect()
          ->intended('dashboard')
          ->withCookie(
            cookie('2fa_verified_' . $user->id, true, 60 * 24 * 15) // 15 days in minutes
          );
      } else {
        session(['2fa_passed' => true]);
        return redirect()
          ->route('dashboard')
          ->with('success', '2FA setup complete.');
      }
    }

    return back()->withErrors(['code' => 'Invalid code. Try again.']);
  }
}
