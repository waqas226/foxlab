<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Device;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;

class CustomerController extends Controller
{
  public function index()
  {
    $customers = Customer::all();
    return view('manage-customers', compact('customers'));
  }

  public function show()
  {
    $customers = Customer::all();

    $customers = $customers->map(function ($customer) {
      $device = Device::where('company_id', $customer->id)->first();
      if ($device) {
        //last_pm = 2025-05-12
        //if current month and last pm month is same, then color = red
        $lastPm = $device->next_pm ? \Carbon\Carbon::parse($device->next_pm) : null;
        if ($lastPm && $lastPm->isCurrentMonth()) {
          $customer->color = 'black';
        } elseif ($lastPm && $lastPm->isPast()) {
          $customer->color = 'red';
        } else {
          $customer->color = 'blue';
        }
      } else {
        $customer->color = 'black';
      }
      return $customer;
    });

    return response()->json(['data' => $customers]);
  }

  public function create($id = null)
  {
    if ($id) {
      $customer = Customer::find($id);
      return view('add-customer', compact('id', 'customer'));
    }
    return view('add-customer');
  }

  public function store(Request $request)
  {
    $request->validate([
      'company' => 'required|string|max:255',
      'primary_contact' => 'required',
      'primary_email' => 'required',
      'primary_phone' => 'required',
      'address' => 'nullable|string|max:255',
      'pm_type' => 'nullable|string|max:255',
      'status' => 'required',
    ]);

    if ($request->id) {
      $customer = Customer::findOrFail($request->id);
      $customer->update($request->all());
      $msg = 'Updated Successfully';
    } else {
      $customer = Customer::create($request->all());
      $msg = 'Added Successfully';
    }
    // Create additional emails

    return response()->json(['status' => true, 'message' => $msg]);
  }
  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|file',
    ]);

    $import = new CustomerImport();

    Excel::import($import, $request->file('file'));
    // return $import->failures();
    // echo json_encode([
    //   'errors' => $import,
    // ]);
    // return;
    return back()->with([
      'success' => "{$import->successfullyImported} Record(s) imported successfully.",
      'failed' => $import->failedRows,
    ]);
  }
  public function status($id)
  {
    $customer = Customer::find($id);

    if ($customer->status == 'A') {
      $customer->status = 'D';
    } else {
      $customer->status = 'A';
    }
    $customer->save();
    return response()->json([
      'status' => true,
      'message' => 'Status updated successfully.',
    ]);
  }

  public function destroy($id)
  {
    $customer = Customer::findOrFail($id);
    $customer->delete();

    return response()->json([
      'status' => true,
      'message' => 'Customer deleted successfully.',
    ]);
  }
}
