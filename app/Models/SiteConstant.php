<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConstant extends Model
{
  use HasFactory;
  //`site_name`, `admin_name`, `admin_email`, `email_link`, `contact_address`, `contact_mobile`, `contact_office`, `idle_timeout`, `smtp_user`, `smtp_password`, `smtp_server`, `updated_version`, `app_version`, `email_template_1`, `renewal_days`, `renewal_link`, `renewal_2`, `renewal_2_link`, `renewal_2_days`, `email_template`, `email_cc`, `auto_send_1`, `auto_send_2`, `idrive`
  protected $fillable = [
    'site_name',
    'admin_name',
    'admin_email',
    'email_link',
    'logo',
    'favicon',
    'contact_office',
    'contact_address',
    'contact_mobile',
    'idle_timeout',
    'company_name',
    'access_token',
    'refresh_token',
    'expires_in',
    'email_template',
    'email_template_warning',
  ];

  protected $casts = [
    'expires_in' => 'integer',
  ];

  public function isOutlookConnected(): bool
  {
    if (empty($this->access_token) || empty($this->refresh_token) || empty($this->expires_in)) {
      return false;
    }

    return (int) $this->expires_in > now()->timestamp;
  }

  public function markOutlookDisconnected(): void
  {
    $this->update([
      'access_token' => null,
      'refresh_token' => null,
      'expires_in' => null,
    ]);
  }
}
