<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Company;
use App\Models\User;
use App\Models\Todo;
use App\Models\Renewal;

class TaskController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $companies = Company::orderBy('name', 'asc')->get();
    return view('tasks-list', compact('companies'));
  }
  public function showData(Request $request)
  {
    $columns = ['updated_at', 'company', 'short_desc', 'enumToDo', 'created_at', 'updated_at', 'status'];

    $query = Task::with('user', 'company');
    $totalData = $query->count();
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    $search = $request->input('search.value');

    $query = Task::with('user', 'company');
    if ($request->company_id) {
      $query->where('company_id', $request->company_id);
    }
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('short_desc', 'like', "%{$search}%")
          ->orWhere('device_affected', 'like', "%{$search}%")
          ->orWhereHas('user', function ($q) use ($search) {
            $names = explode(' ', $search, 2);
            if (count($names) == 2) {
              $q->where(function ($query) use ($names) {
                $query->where('firstname', 'like', "%{$names[0]}")->where('lastname', 'like', "{$names[1]}%");
              });
            } else {
              $q->where('firstname', 'like', "%{$search}%")->orWhere('lastname', 'like', "%{$search}%");
            }
          })
          ->orWhereHas('company', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
          });
      });
    }

    if ($request->input('task_status')) {
      $status = $request->input('task_status');
      if ($status == 'Open') {
        $query->where(function ($q) {
          $q->where('tasks.status', 'Open')->orWhere('tasks.status', 'Pending');
        });
      } else {
        $query->where('tasks.status', $status);
      }
    }

    if ($order == 'company') {
      $tasks = $query
        ->join('companies', 'tasks.company_id', '=', 'companies.id')
        ->select('tasks.*', 'companies.name as company_name')
        ->orderBy('companies.name', $dir);
    } else {
      $tasks = $query->orderBy($order, $dir);
    }

    return response()->json([
      'data' => $tasks->get(),
      'draw' => $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $tasks->count(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $companies = Company::all();

    return view('add-task', compact('companies'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validate the request
    $request->validate([
      'priority' => 'required',
      'company_id' => 'required|exists:companies,id',
      'device_affected' => 'required',
      'short_desc' => 'required',
    ]);

    //if file is uploaded
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads'), $filename);
    } else {
      $filename = null;

      // return response()->json([
      //   'status' => false,
      //   'message' => 'File not found',
      // ]);
    }

    // Create a new task
    if (isset($request->id) && $request->id != '') {
      $task = Task::find($request->id);
      $msg = 'Task Updated Successfully';
      $type = 'update';
      if (!$task) {
        return response()->json([
          'status' => false,
          'message' => 'Task not found',
        ]);
      }
    } else {
      $task = new Task();
      $msg = 'Task Created Successfully';
      $type = 'create';
    }

    $task->user_id = auth()->user()->id;
    $task->company_id = $request->company_id;
    $task->enumToDo = $request->priority;
    $task->device_affected = $request->device_affected;
    $task->short_desc = $request->short_desc;
    // $task->long_desc = $request->long_desc;
    $task->status = 'Open';
    $task->task_date = $request->task_date;
    $task->ip_address = $_SERVER['REMOTE_ADDR'];
    $task->error_image = $filename;
    $task->save();

    return response()->json([
      'status' => true,
      'type' => $type,
      'message' => $msg,
      'id' => $task->id,
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $companies = Company::all();
    $task = Task::find($id);

    if (!$task) {
      return redirect()
        ->route('tasks.index')
        ->with('error', 'Task not found');
    }

    return view('add-task', compact('companies', 'id', 'task'));
  }

  //updatelongdesc
  public function updatelongdesc(Request $request, string $id)
  {
    $task = Task::find($id);
    if (!$task) {
      return response()->json([
        'status' => false,
        'message' => 'Task not found',
      ]);
    }

    $task->long_desc = $request->long_desc;
    $task->save();

    return response()->json([
      'status' => true,
      'message' => 'Task updated successfully',
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  public function updatestatus(Request $request, $id)
  {
    $task = Task::find($id);
    if (!$task) {
      return response()->json([
        'status' => false,
        'message' => 'Task not found',
      ]);
    }

    $task->status = $request->status;
    $task->save();

    return response()->json([
      'status' => true,
      'message' => 'Task status updated successfully',
    ]);
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
  public function delete($id)
  {
    $task = Task::find($id);
    if (!$task) {
      return response()->json([
        'status' => false,
        'message' => 'Task not found',
      ]);
    }

    $task->delete();

    return response()->json([
      'status' => true,
      'message' => 'Task deleted successfully',
    ]);
  }
  public function print(Request $request, $taskId)
  {
    $task = Task::where('id', $taskId)
      ->with('user')
      ->with('company')
      ->first();
    $todo = $request->todo;

    $todos = Todo::where('company_id', $task->company_id)
      ->orderBy('toodo_date')
      ->get();
    $renewals = Renewal::where('company_id', $task->company_id)
      ->orderBy('renewal_date')
      ->get();
    return view('print-task', [
      'task' => $task,
      'todos' => $todos,
      'renewals' => $renewals,
      'todoprint' => $todo,
    ]);
  }
}
