<?php

namespace App\Mail;

use App\Models\SiteConstant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorkOrderMail
{
  public function sendMail(
    $to,
    $bcc ,
    $subject,
    $body,
    $pdfBinary = null,
    $filename = 'attachment.pdf',
    $fromName = 'Foxlab Support'
  ) {
    $record = SiteConstant::find(1);
    if (!$record) {
      return [
        'success' => false,
        'status' => 500,
        'message' => 'Outlook configuration is missing in site constants.',
      ];
    }

    try {
      $accessToken = $this->getValidAccessToken($record);
    } catch (\Throwable $e) {
      Log::error('Outlook token refresh failed: ' . $e->getMessage());
      if ($this->isPermanentRefreshFailure($e->getMessage())) {
        $record->markOutlookDisconnected();
      }

      return [
        'success' => false,
        'status' => 401,
        'message' => 'Outlook token expired and refresh failed. Reconnect Outlook.',
      ];
    }

    $url = 'https://graph.microsoft.com/v1.0/me/sendMail';

    $toRecipients = [
      [
        'emailAddress' => ['address' =>$to],
      ],
    ];

    $bccRecipients = [];
    if (!empty($bcc)) {
      foreach ((array) $bcc as $bccEmail) {
        $bccRecipients[] = ['emailAddress' => ['address' => $bccEmail]];
      }
    }

    $attachments = [];
    if ($pdfBinary) {
      $attachments[] = [
        '@odata.type' => '#microsoft.graph.fileAttachment',
        'name' => $filename,
        'contentType' => 'application/pdf',
        'contentBytes' => base64_encode($pdfBinary),
      ];
    }

    $payload = [
      'message' => [
        'subject' => $subject,
        'body' => [
          'contentType' => 'HTML',
          'content' => $body,
        ],
        'toRecipients' => $toRecipients,
        'bccRecipients' => $bccRecipients,
        'attachments' => $attachments,
        'from' => [
          'emailAddress' => [
            'address' => 'bev@foxlablogistics.com',
            'name' => $fromName,
          ],
        ],
      ],
      'saveToSentItems' => true,
    ];

    $response = $this->sendGraphMailRequest($url, $accessToken, $payload);

    if ($response->status() === 401) {
      try {
        $accessToken = $this->refreshAccessToken($record, true);
        $response = $this->sendGraphMailRequest($url, $accessToken, $payload);
      } catch (\Throwable $e) {
        Log::error('Outlook token retry failed: ' . $e->getMessage());
      }
    }

    if ($response->successful()) {
      return ['success' => true];
    }

    return [
      'success' => false,
      'status' => $response->status(),
      'message' => $response->body(),
    ];
  }

  private function sendGraphMailRequest(string $url, string $accessToken, array $payload)
  {
    return Http::withToken($accessToken)
      ->withHeaders([
        'Content-Type' => 'application/json',
      ])
      ->post($url, $payload);
  }

  private function getValidAccessToken(SiteConstant $record): string
  {
    if ($this->tokenNeedsRefresh($record)) {
      return $this->refreshAccessToken($record);
    }

    return $record->access_token;
  }

  private function tokenNeedsRefresh(SiteConstant $record): bool
  {
    if (empty($record->access_token)) {
      return true;
    }

    $bufferSeconds = 120;

    if (!empty($record->expires_in) && is_numeric($record->expires_in)) {
      return time() >= ((int) $record->expires_in - $bufferSeconds);
    }

    return $this->isJwtExpired($record->access_token, $bufferSeconds);
  }

  private function isJwtExpired(string $accessToken, int $bufferSeconds = 0): bool
  {
    try {
      list(, $payload) = explode('.', $accessToken);
      $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

      return !isset($decoded['exp']) || ((int) $decoded['exp'] <= (time() + $bufferSeconds));
    } catch (\Throwable $e) {
      return true;
    }
  }

  private function refreshAccessToken(SiteConstant $record, bool $force = false): string
  {
    if (!$force && !$this->tokenNeedsRefresh($record)) {
      return $record->access_token;
    }

    if (empty($record->refresh_token)) {
      throw new \RuntimeException('Missing refresh token.');
    }

    $outlookConfig = config('services.outlook');
    $scopes = $outlookConfig['scopes'] ?? ['openid', 'profile', 'offline_access', 'Mail.Send'];
    $tenantId = $outlookConfig['tenant_id'] ?? null;

    if (empty($tenantId) || empty($outlookConfig['client_id']) || empty($outlookConfig['client_secret'])) {
      throw new \RuntimeException('Outlook credentials are not configured.');
    }

    $response = Http::asForm()->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
      'grant_type' => 'refresh_token',
      'refresh_token' => $record->refresh_token,
      'client_id' => $outlookConfig['client_id'],
      'client_secret' => $outlookConfig['client_secret'],
      'scope' => implode(' ', $scopes),
    ]);

    if ($response->successful() && isset($response['access_token'])) {
      $data = $response->json();
      $newRefreshToken = $data['refresh_token'] ?? $record->refresh_token;
      $expiresAt = $this->resolveExpiresAt($data);

      $record->update([
        'access_token' => $data['access_token'],
        'refresh_token' => $newRefreshToken,
        'expires_in' => $expiresAt,
      ]);

      return $data['access_token'];
    }

    $errorDescription = $response->json('error_description') ?: $response->body();
    throw new \RuntimeException('Failed to regenerate Microsoft Graph access token. ' . $errorDescription);
  }

  private function resolveExpiresAt(array $tokenResponse): int
  {
    if (!empty($tokenResponse['expires_on']) && is_numeric($tokenResponse['expires_on'])) {
      return (int) $tokenResponse['expires_on'];
    }

    if (!empty($tokenResponse['expires_in']) && is_numeric($tokenResponse['expires_in'])) {
      return time() + (int) $tokenResponse['expires_in'];
    }

    return time() + 3600;
  }

  private function isPermanentRefreshFailure(string $message): bool
  {
    $permanentErrors = [
      'invalid_grant',
      'interaction_required',
      'invalid_client',
      'missing refresh token',
    ];

    foreach ($permanentErrors as $needle) {
      if (stripos($message, $needle) !== false) {
        return true;
      }
    }

    return false;
  }
}
