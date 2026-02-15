<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;
use App\Models\User;
class StatisticController extends Controller
{
  public function index()
  {
    return view('statistics');
  }
  public function show()
  {
    $statistics = Statistic::with('user')->get();
    return response()->json(['data' => $statistics]);
  }
}
