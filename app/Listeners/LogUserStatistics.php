<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Http\Request;
use App\Models\Statistic;
// use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class LogUserStatistics
{
  public function handle(Login $event)
  {
    $request = request();
    $ip = $request->ip();
    $userAgent = $request->header('User-Agent');

    // Fallback parser (basic)
    $browser = $this->getBrowser($userAgent);
    $os = $this->getOS($userAgent);
    $carrier = $this->getCarrier($ip);

    Statistic::create([
      'user_id' => $event->user->id,
      'ip_address' => $ip,
      'browser_name' => $browser['name'],
      'browser_version' => $browser['version'],
      'os_platform' => $os,
      'carrier_name' => $carrier,
    ]);
  }
  private function getCarrier($ip)
  {
    $response = Http::get("http://ip-api.com/json/{$ip}?fields=isp");
    return $response->json('isp') ?? 'Unknown';
  }
  private function getBrowser($userAgent)
  {
    $browser = ['name' => 'Unknown', 'version' => ''];

    if (preg_match('/(Firefox|Chrome|Safari|Opera|MSIE|Trident)\/?\s*([\d.]+)/i', $userAgent, $matches)) {
      $browser['name'] = $matches[1] == 'Trident' ? 'Internet Explorer' : $matches[1];
      $browser['version'] = $matches[2] ?? '';
    }

    return $browser;
  }

  private function getOS($userAgent)
  {
    $osArray = [
      'Windows' => 'Windows',
      'Macintosh' => 'Mac OS',
      'iPhone' => 'iOS',
      'iPad' => 'iOS',
      'Android' => 'Android',
      'Linux' => 'Linux',
    ];

    foreach ($osArray as $key => $os) {
      if (stripos($userAgent, $key) !== false) {
        return $os;
      }
    }

    return 'Unknown OS';
  }
}
