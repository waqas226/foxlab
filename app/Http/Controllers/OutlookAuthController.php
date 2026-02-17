<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TheNetworg\OAuth2\Client\Provider\Azure;
use App\Models\SiteConstant;

class OutlookAuthController extends Controller
{
  private function outlookProvider(): Azure
  {
    $outlookConfig = config('services.outlook');

    $provider = new Azure([
      'clientId' => $outlookConfig['client_id'],
      'clientSecret' => $outlookConfig['client_secret'],
      'redirectUri' => $outlookConfig['redirect_uri'],
      'tenant' => $outlookConfig['tenant_id'],
    ]);

    $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;

    return $provider;
  }

  public function redirectToProvider()
  {
    $provider = $this->outlookProvider();
    $authorizationUrl = $provider->getAuthorizationUrl([
      'scope' => config('services.outlook.scopes'),
    ]);

    session(['oauth2state' => $provider->getState()]);

    return redirect($authorizationUrl);
  }

  public function handleProviderCallback(Request $request)
  {
    $provider = $this->outlookProvider();

    // ✅ CSRF protection: validate state
    if (empty($request->state) || $request->state !== session('oauth2state')) {
      abort(403, 'Invalid state. Try connecting Outlook again.');
    }

    try {
      // ✅ Exchange auth code for access + refresh token
      $token = $provider->getAccessToken('authorization_code', [
        'code' => $request->code,
      ]);

      $constant = SiteConstant::firstOrFail();
      $refreshToken = $token->getRefreshToken();

      if (empty($refreshToken)) {
        throw new \RuntimeException('Missing refresh token from Microsoft. Ensure offline_access scope is granted.');
      }

      $constant->update([
        'access_token' => $token->getToken(),
        'refresh_token' => $refreshToken,
        'expires_in' => $token->getExpires(), // Unix timestamp
      ]);

      return redirect('/')->with('success', 'Outlook Connected!');
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
      \Log::error('OAuth Callback Error: ' . $e->getMessage());
      return redirect('/')->with('error', 'Failed to connect to Outlook.');
    }
  }
}
