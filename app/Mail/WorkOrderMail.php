<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

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
    $record = DB::table('site_constants')
      ->where('id', 1)
      ->first();
    $accessToken = $record->access_token;

    // 2. Check if token is expired
    if ($this->isTokenExpired($accessToken)) {
      $accessToken = $this->regenerateAccessToken(
        $record->refresh_token,
         env('OUTLOOK_CLIENT_ID'),
        env('OUTLOOK_CLIENT_SECRET'),
        '04b9e953-510e-4e3f-bc0b-ca1cc5b52acb'
      );

      // Save the refreshed token
      DB::table('site_constants')
        ->where('id', 1)
        ->update(['access_token' => $accessToken]);
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
            'name' => 'Fox Lab Logistics',
          ],
        ],
      ],
      'saveToSentItems' => true,
    ];

    $response = Http::withToken($accessToken)
      ->withHeaders([
        'Content-Type' => 'application/json',
      ])
      ->post($url, $payload);

    if ($response->successful()) {
      return ['success' => true];
    }

    return [
      'success' => false,
      'status' => $response->status(),
      'message' => $response->body(),
    ];
  }
  private function isTokenExpired($accessToken)
  {
    try {
      list(, $payload) = explode('.', $accessToken);
      $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

      return isset($decoded['exp']) && $decoded['exp'] < time();
    } catch (\Throwable $e) {
      return true;
    }
  }
  private function regenerateAccessToken($refreshToken, $clientId, $clientSecret, $tenantId)
  {
    $response = Http::asForm()->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
      'grant_type' => 'refresh_token',
      'refresh_token' => $refreshToken,
      'client_id' => 'e67a0188-1cc7-42f6-928e-495f1964e90d',
      'client_secret' => 'fSi8Q~UcqzkZyk2NCK71FlDZQ_uONYoZE1mfXcRJ',
      'scope' => 'https://graph.microsoft.com/.default',
    ]);

    if ($response->successful() && isset($response['access_token'])) {
      return $response['access_token'];
    }

    throw new \Exception('Failed to regenerate Microsoft Graph access token.'. $response->json('error_description'));
  }
}
