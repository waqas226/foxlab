<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Fortify::createUsersUsing(CreateNewUser::class);
    Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    RateLimiter::for('login', function (Request $request) {
      $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

      return Limit::perMinute(5)->by($throttleKey);
    });

    RateLimiter::for('two-factor', function (Request $request) {
      return Limit::perMinute(5)->by($request->session()->get('login.id'));
    });
    Fortify::twoFactorChallengeView(function () {
      return view('auth.two-factor-challenge');
    });

    Fortify::authenticateThrough(function (Request $request) {
      return array_filter([
        // If a remembered device cookie is valid, skip 2FA challenge
        function ($request) {
          $user = Auth::user();
          $cookie = $request->cookie('remember_2fa');

          if ($cookie) {
            $token = hash('sha256', $cookie);
            $device = $user
              ->rememberedDevices()
              ->where('token', $token)
              ->where('expires_at', '>', now())
              ->first();

            if ($device) {
              session()->put('login.id', $user->id);
              Auth::login($user);
              return redirect()->intended(config('fortify.home'));
            }
          }
        },

        Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable::class,
        Laravel\Fortify\Actions\EnsureLoginIsNotThrottled::class,
        Laravel\Fortify\Actions\AttemptToAuthenticate::class,
        Laravel\Fortify\Actions\PrepareAuthenticatedSession::class,
      ]);
    });
  }
}
