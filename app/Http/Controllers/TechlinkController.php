<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Techlink;

class TechlinkController extends Controller
{
  public function index()
  {
    return view('manage-techlinks');
  }
  public function show()
  {
    $techlinks = Techlink::all();
    return response()->json(['data' => $techlinks]);
  }
  public function create()
  {
    return view('add-techlink');
  }
  public function edit($id)
  {
    $techlink = Techlink::find($id);
    return view('add-techlink', compact('techlink', 'id'));
  }
  public function store(Request $request)
  {
    $request->validate([
      'description' => 'required',
      'link' => 'required|url',
    ]);
    $id = $request->input('id');
    // Validate the request data
    if ($id) {
      $techlink = Techlink::find($id);
      if (!$techlink) {
        return response()->json([
          'status' => false,
          'message' => 'Techlink not found!',
        ]);
      }
    } else {
      $techlink = new Techlink();
    }

    $techlink->description = $request->input('description');
    $techlink->link = $request->input('link');
    if ($request->hasFile('file')) {
      // $techlink->image = $request->file('file')->store('techlinks', 'public');
      //original file name
      $file = $request->file('file');
      $filename = $request->file('file')->getClientOriginalName();
      $file->move(public_path('uploads'), $filename);
      $techlink->image = 'uploads/' . $filename;
    }
    $techlink->notes = $request->input('notes');

    // Save the techlink to the database
    $techlink->save();
    // Redirect back with success message
    return redirect()
      ->route('manage-techlinks')
      ->with('success', 'Techlink created successfully');
    return response()->json([
      'status' => true,
      'message' => 'Techlink created successfully',
    ]);
  }
  public function destroy($id)
  {
    $techlink = Techlink::find($id);
    if ($techlink) {
      $techlink->delete();
      return response()->json([
        'status' => true,
        'message' => 'Techlink deleted successfully',
      ]);
    } else {
      return response()->json([
        'status' => false,
        'message' => 'Techlink not found',
      ]);
    }
  }
  public function file_delete($id)
  {
    $techlink = Techlink::find($id);
    if ($techlink) {
      $techlink->image = null;
      $techlink->save();

      // Delete the file from the server
      $filePath = public_path($techlink->image);
      if (file_exists($filePath)) {
        //  unlink($filePath);
      }
      return response()->json([
        'status' => true,
        'message' => 'File deleted successfully',
      ]);
    } else {
      return response()->json([
        'status' => false,
        'message' => 'Techlink not found',
      ]);
    }
  }
}
