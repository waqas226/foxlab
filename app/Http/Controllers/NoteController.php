<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // Fetch all notes from the database
    $notes = Note::all();

    // Return the view with the notes data
    return view('manage-notes', compact('notes'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Return the view for creating a new note
    return view('add-notes');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validate the request data
    $request->validate([
      'title' => 'required',
    ]);

    $id = $request->input('id');

    if ($id) {
      // Find the note by ID
      $note = Note::find($id);
    } else {
      // Create a new note instance
      $note = new Note();
    }
    // Check if the note exists

    $note->title = $request->input('title');
    $note->items = $request->input('items');

    // Save the note to the database
    $note->save();

    // Redirect back with success message
    return response()->json([
      'status' => true,
      'message' => 'Note created successfully',
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $notes = Note::all();
    $json_data = [
      'data' => $notes,
    ];
    return response()->json($json_data);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    // Find the note by ID
    $note = Note::find($id);
    return view('add-notes', compact('note', 'id'));
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
    // Find the note by ID
    $note = Note::find($id);

    // Check if the note exists
    if (!$note) {
      return response()->json([
        'status' => false,
        'message' => 'Note not found',
      ]);
    }

    // Delete the note
    $note->delete();

    // Return success response
    return response()->json([
      'status' => true,
      'message' => 'Note deleted successfully',
    ]);
  }

  public function print($id)
  {
    $note = Note::find($id);
    if (!$note) {
      return response()->json([
        'status' => false,
        'message' => 'Note not found',
      ]);
    }

    return view('print-notes', compact('note'));
  }
}
