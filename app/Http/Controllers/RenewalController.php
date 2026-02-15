<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Renewal;
use App\Models\Todo;
use App\Models\Task;

class RenewalController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $companies = Company::orderBy('name', 'asc')->get();
    return view('manage-renewal', compact('companies'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required',
      'renewal_date' => 'required|date',
    ]);

    if ($request->input('id')) {
      $todo = Renewal::find($request->input('id'));
      if (!$todo) {
        return response()->json([
          'status' => false,
          'message' => 'Renewal not found!',
        ]);
      }
      $res = [
        'status' => true,
        'message' => 'Renewal updated successfully',
        'todo' => $todo,
      ];
    } else {
      $todo = new Renewal();
      $todo->company_id = $request->input('company_id');
      $todo->user_id = auth()->user()->id;
      $res = [
        'status' => 'success',
        'message' => 'Renewal saved successfully',
        'todo' => $todo,
      ];
    }

    $todo->title = $request->input('title');
    $todo->renewal_date = $request->input('renewal_date');
    $todo->save();
    return response()->json($res);
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    $columns = ['id', 'title', 'company_id', 'renewal_date'];
    $companies = Company::all();
    $query = Renewal::with('user', 'company');
    $totalData = $query->count();
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    $search = $request->input('search.value');

    $query = Renewal::with('user', 'company');

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")->orWhereHas('company', function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%");
        });
      });
    }
    $month = $request->input('month');
    if ($month) {
      $query->where(function ($q) use ($month) {
        $q->whereMonth('renewal_date', $month);
        $q->whereYear('renewal_date', date('Y'));
      });
    } elseif ($request->input('company_id') == 'all') {
    } elseif ($request->input('company_id')) {
      $query->where('company_id', $request->input('company_id'));
    } else {
      return response()->json([
        'data' => [],
        'draw' => $request->input('draw'),
        'recordsTotal' => $totalData,
        'recordsFiltered' => 0,
      ]);
    }

    $tasks = $query->orderBy($order, $dir);
    return response()->json([
      'data' => $tasks->get(),
      'draw' => $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $tasks->count(),
    ]);
  }

  public function showData($id)
  {
    $task = Task::find($id);
    $todo = Renewal::where('company_id', $task->company_id)->get();

    return response()->json([
      'status' => true,
      'data' => $todo,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $renewal = Renewal::find($id);
    if ($renewal) {
      $renewal->delete();
      return response()->json(['status' => true, 'message' => 'Renewal deleted successfully']);
    } else {
      return response()->json(['status' => false, 'message' => 'Renewal not found']);
    }
  }

  public function transfer($id)
  {
    $renewal = Renewal::find($id);
    if (!$renewal) {
      return response()->json([
        'status' => false,
        'message' => 'Renewal not found!',
      ]);
    }

    $todo = new Todo();
    $todo->company_id = $renewal->company_id;
    $todo->user_id = $renewal->user_id;
    $todo->toodo_date = $renewal->renewal_date;
    $todo->next_visit_todo = $renewal->title;
    $todo->created_at = $renewal->created_at;
    $todo->save();
    $renewal->delete();
    return response()->json([
      'status' => true,
      'message' => 'Renewal transferred successfully',
      'todo' => $renewal,
    ]);
  }
  public function print($company_id)
  {
    $company = Company::find($company_id);
    if (!$company) {
      return response()->json([
        'status' => false,
        'message' => 'Company not found!',
      ]);
    }
    $renewals = Renewal::where('company_id', $company_id)->get();
    return view('print-renewal', compact('renewals', 'company'));
  }
}
