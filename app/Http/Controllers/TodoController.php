<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Company;
use App\Models\Renewal;
use App\Models\Task;

class TodoController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $companies = Company::orderBy('name', 'asc')->get();
    return view('manage-todo', compact('companies'));
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
      'next_visit_todo' => 'required',
      'toodo_date' => 'required|date',
    ]);

    if ($request->input('id')) {
      $todo = Todo::find($request->input('id'));
      if (!$todo) {
        return response()->json([
          'status' => false,
          'message' => 'Todo not found!',
        ]);
      }
      $res = [
        'status' => true,
        'message' => 'Todo updated successfully',
        'todo' => $todo,
      ];
    } else {
      $todo = new Todo();
      $todo->company_id = $request->input('company_id');
      $todo->user_id = auth()->user()->id;
      $res = [
        'status' => true,
        'message' => 'Todo saved successfully',
        'todo' => $todo,
      ];
    }

    $todo->next_visit_todo = $request->input('next_visit_todo');
    $todo->toodo_date = $request->input('toodo_date');
    $todo->save();
    return response()->json($res);
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    $columns = ['id', 'next_visit_todo', 'company_id', 'toodo_date'];
    $companies = Company::all();
    $query = Todo::with('user', 'company');
    $totalData = $query->count();
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    $search = $request->input('search.value');
    // columns[3][search][value]

    $query = Todo::with('user', 'company');

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('next_visit_todo', 'like', "%{$search}%")->orWhereHas('company', function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%");
        });
      });
    }
    $month = $request->input('month');
    if ($month) {
      $query->where(function ($q) use ($month) {
        $q->whereMonth('toodo_date', $month);
        $q->whereYear('toodo_date', date('Y'));
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

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  public function showData($id)
  {
    $task = Task::find($id);
    $todo = Todo::where('company_id', $task->company_id)->get();

    return response()->json([
      'status' => true,
      'data' => $todo,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    echo 'ok';
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $todo = Todo::find($id);
    if (!$todo) {
      return response()->json([
        'status' => false,
        'message' => 'Todo not found!',
      ]);
    }
    $todo->delete();
    return response()->json([
      'status' => true,
      'message' => 'Todo deleted successfully',
    ]);
  }

  public function transfer($id)
  {
    $todo = Todo::find($id);
    if (!$todo) {
      return response()->json([
        'status' => false,
        'message' => 'Todo not found!',
      ]);
    }

    $renewal = new Renewal();
    $renewal->company_id = $todo->company_id;
    $renewal->user_id = auth()->user()->id;
    $renewal->title = $todo->next_visit_todo;
    $renewal->renewal_date = $todo->toodo_date;
    $renewal->created_at = $todo->created_at;
    $renewal->save();
    $todo->delete();

    return response()->json([
      'status' => true,
      'message' => 'Todo transferred successfully',
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
    $todos = Todo::where('company_id', $company_id)->get();
    return view('print-todo', compact('todos', 'company'));
  }
}
