<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Task;
use App\Models\Device;
use App\Imports\ChecklistImport;
use Maatwebsite\Excel\Facades\Excel;

class ChecklistController extends Controller
{
  public function index()
  {
    $checklists = Checklist::with('tasks')->get();
    return view('manage-checklists', compact('checklists'));
  }
  public function show()
  {
    $checklists = Checklist::get();
    return response()->json([
      'data' => $checklists,
    ]);
  }
  public function create()
  {
    // $makes = Device::distinct()->pluck('make');
    //get all models if they are not attached with the checklist
    // $models = Device::distinct()->pluck('model');
    return view('add-checklist');
  }

  public function edit($id)
  {
    // $makes = Device::distinct()->pluck('make');

    $checklist = Checklist::with('tasks')->findOrFail($id);

    // $modelSelected = Checklist::where('device_id', '!=', $checklist->device_id)
    //   ->distinct()
    //   ->pluck('device_id')
    //   ->toArray();
    // $models = Device::whereNotIn('id', $modelSelected)
    //   ->where('make', $checklist->make)
    //   ->select('id', 'model')
    //   ->get();

    if (!$checklist) {
      return redirect()
        ->route('manage-checklists')
        ->with('error', 'Checklist not found');
    }
    return view('add-checklist', compact('checklist'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'tasks.*.title' => 'required|string',
      'tasks.*.description' => 'nullable|string',
    ]);

    if ($request->id) {
      // $check = Checklist::where('device_id', $request->model)
      //   ->where('id', '!=', $request->id)
      //   ->count();
      // if ($check > 0) {
      //   return response()->json([
      //     'status' => false,
      //     'message' => 'Model already associated with a Checklist',
      //   ]);
      // }

      // $device = Device::find($request->model);
      $checklist = Checklist::findOrFail($request->id);
      $checklist->title = $request->title;
      // $checklist->make = $request->make;
      // $checklist->model = $device->model;
      // $checklist->device_id = $request->model;
      $checklist->save();
      $msg = 'Checklist updated successfully';
      // Clear existing tasks
      $checklist->tasks()->delete();
    } else {
      // $check = Checklist::where('device_id', $request->model)->count();
      // if ($check > 0) {
      //   return response()->json([
      //     'status' => false,
      //     'message' => 'Model already associated with a Checklist',
      //   ]);
      // }

      $checklist = Checklist::create([
        'title' => $request->title,
      ]);
      $msg = 'Checklist added successfully';
    }

    foreach ($request->task_title as $key => $title) {
      if ($title != '') {
        $taskData = ['title' => $title, 'description' => ''];
        $checklist->tasks()->create($taskData);
      }
    }
    return response()->json([
      'status' => true,
      'message' => $msg,
    ]);
  }
  public function getModels($make, $id = null)
  {
    // $modelSelected = Checklist::distinct()
    //   ->where('id', '!=', $id)
    //   ->pluck('device_id')
    //   ->toArray();
    $models = Device::distinct()
      ->where('make', $make)

      ->pluck('model')
      ->toArray();
    return response()->json([
      'status' => true,
      'data' => $models,
    ]);
  }

  public function getMakes()
  {
    // $modelSelected = Checklist::distinct()
    //   ->where('id', '!=', $id)
    //   ->pluck('device_id')
    //   ->toArray();
    $models = Device::distinct()
      ->pluck('make')
      ->toArray();
    return response()->json([
      'status' => true,
      'data' => $models,
    ]);
  }
  public function getTypes()
  {
    // $modelSelected = Checklist::distinct()
    //   ->where('id', '!=', $id)
    //   ->pluck('device_id')
    //   ->toArray();
    $models = Device::distinct()
      ->pluck('device_type')
      ->toArray();
    return response()->json([
      'status' => true,
      'data' => $models,
    ]);
  }

  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    $import = new ChecklistImport();
    Excel::import($import, $request->file('file'));

    return back()->with([
      'success' => "{$import->successfullyImported} of {$import->totalRows} Checklist rows imported.",
      'failed' => $import->failedRows,
    ]);
  }
  public function print($id)
  {
    $checklist = Checklist::with('tasks')->findOrFail($id);
    if (!$checklist) {
      return redirect()
        ->route('manage-checklists')
        ->with('error', 'Checklist not found');
    }
    return view('print-checklist', compact('checklist'));
  }
  public function destroy($id)
  {
    $checklist = Checklist::findOrFail($id);
    $checklist->tasks()->delete();
    $checklist->delete();

    return response()->json([
      'status' => true,
      'message' => 'Checklist deleted successfully.',
    ]);
  }
}
