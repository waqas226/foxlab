<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TheNetworg\OAuth2\Client\Provider\Azure;
use App\Models\SiteConstant;

class OutlookAuthController extends Controller
{

  public function redirectToProvider()
  {
    $constant = SiteConstant::first();
    $provider = new Azure([
      'clientId' => 'e67a0188-1cc7-42f6-928e-495f1964e90d',
      'clientSecret' => 'fSi8Q~UcqzkZyk2NCK71FlDZQ_uONYoZE1mfXcRJ',
      'redirectUri' => 'https://dev.foxlablogistics.com/callback',
      'tenant' => '04b9e953-510e-4e3f-bc0b-ca1cc5b52acb',
    ]);

    $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;
    $authorizationUrl = $provider->getAuthorizationUrl([
      'scope' => ['openid', 'profile', 'offline_access', 'Mail.Send'],
    ]);

    session(['oauth2state' => $provider->getState()]);

    return redirect($authorizationUrl);
  }

  public function handleProviderCallback(Request $request)
  {
    $provider = new Azure([
      'clientId' => 'e67a0188-1cc7-42f6-928e-495f1964e90d',
      'clientSecret' => 'fSi8Q~UcqzkZyk2NCK71FlDZQ_uONYoZE1mfXcRJ',
      'redirectUri' => 'https://dev.foxlablogistics.com/callback',
      'tenant' => '04b9e953-510e-4e3f-bc0b-ca1cc5b52acb',
    ]);

    // ✅ Force Microsoft Identity Platform v2.0
    $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;

    // ✅ CSRF protection: validate state
    if (empty($request->state) || $request->state !== session('oauth2state')) {
      abort(403, 'Invalid state. Try connecting Outlook again.');
    }

    try {
      // ✅ Exchange auth code for access + refresh token
      $token = $provider->getAccessToken('authorization_code', [
        'code' => $request->code,
      ]);

      // ✅ Save tokens to DB
      $constant = SiteConstant::first();
      $constant->update([
        'access_token' => $token->getToken(),
        'refresh_token' => $token->getRefreshToken(),
        'expires_in' => $token->getExpires(), // Unix timestamp
      ]);

      return redirect('/')->with('success', 'Outlook Connected!');
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
      \Log::error('OAuth Callback Error: ' . $e->getMessage());
      return redirect('/')->with('error', 'Failed to connect to Outlook.');
    }
  }
}
