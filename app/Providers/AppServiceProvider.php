<?php

namespace App\Providers;
use PragmaRX\Google2FA\Google2FA;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->singleton('pragmarx.google2fa', function () {
      return new Google2FA();
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
