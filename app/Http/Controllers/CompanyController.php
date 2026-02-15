<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Device;

class CompanyController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $companies = Company::all();
    return view('company-list', compact('companies'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function showData()
  {
    $companies = Company::orderBy('name', 'asc')->get();
    $json_data = [
      'data' => $companies,
    ];
    return response()->json($json_data);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //valide the request ,name require and unique
    $request->validate([
      'name' => 'required|unique:companies',
    ]);

    if (isset($request->id) && $request->id > 0) {
      $company = Company::find($request->id);
      Device::where('newCompany', $company->name)->update(['newCompany' => $request->name]);
    } else {
      //create new company
      $company = new Company();
    }
    $company->name = $request->name;
    $company->save();
    return response()->json(['success' => 'Company added successfully.']);
  }

  // updatestatus
  public function updatestatus(Request $request)
  {
    $request->validate([
      'id' => 'required',
      'status' => 'required',
    ]);

    $company = Company::find($request->id);
    if (!$company) {
      return response()->json(['status' => false, 'message' => 'Company not found.']);
    }
    $company->status = $request->status;
    $company->save();
    return response()->json(['status' => true, 'message' => 'Company status updated successfully.']);
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
    if (Company::destroy($id)) {
      return response()->json(['success' => 'Company deleted successfully.']);
    } else {
      return response()->json(['error' => 'Technical error occurred, please try again.']);
    }
  }
}
