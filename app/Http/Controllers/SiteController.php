<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteConstant;

class SiteController extends Controller
{
  public function index()
  {
    $constant = SiteConstant::first();

    return view('site-constants', compact('constant'));
  }
  public function store(Request $request)
  {
    $request->validate([
      'site_name' => 'required',
      'idle_timeout' => 'required',
    ]);

    $constant = SiteConstant::first();
    $logo = $constant->logo;
    $favicon = $constant->favicon;
    if ($request->hasFile('logo')) {
      $file = $request->file('logo');
      $logo = 'logo.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads'), $logo);
    }

    if ($request->hasFile('favicon')) {
      $file = $request->file('favicon');
      $favicon = 'favicon.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads'), $favicon);
    }

    $constant->site_name = $request->site_name;
    $constant->idle_timeout = $constant->idle_timeout;
    $constant->logo = $logo;
    $constant->favicon = $favicon;
    $constant->contact_address = $request->contact_address;
    $constant->contact_mobile = $request->contact_mobile;
    $constant->contact_office = $request->contact_office;
    $constant->company_name = $request->company_name;
     $constant->email_template = $request->email_template;
    $constant->save();

    return response()->json(['status' => true, 'message' => 'Site constants updated successfully.']);
  }
}
