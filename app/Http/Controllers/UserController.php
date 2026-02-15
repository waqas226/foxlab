<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AdditionalEmail;
use App\Models\Company;
use App\Models\Role;

class UserController extends Controller
{
  public function index()
  {
    $companies = Company::orderBy('name', 'asc')->get();
    return view('manage-user', compact('companies'));
  }
  # MAKE METHOD TO SHOW DATA IN DATA TABLE WITH AJAX REQUEST AND RETURN JSON RESPONSE
  public function showData(Request $request)
  {
    $columns = [
      0 => 'id',
      1 => 'username',
      2 => 'email',
      3 => 'firstname',
      4 => 'lastname',
      5 => 'company',
    ];
    // get all user with there additional emails
    $users = User::with('additionalEmails')
      ->with('company')
      ->with('role')
      
      ->get();

    $json_data = [
      'data' => $users,
    ];
    echo json_encode($json_data);

    return;

    $totalData = User::count();
    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $users = User::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->with('additionalEmails')
        ->with('company')
        ->get();
    } else {
      $search = $request->input('search.value');

      $users = User::where('id', 'LIKE', "%{$search}%")
        ->orWhere('username', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhere('firstname', 'LIKE', "%{$search}%")
        ->orWhere('lastname', 'LIKE', "%{$search}%")

        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->with('additionalEmails')
        ->with('company')
        ->get();

      $totalFiltered = User::where('id', 'LIKE', "%{$search}%")
        ->orWhere('username', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhere('firstname', 'LIKE', "%{$search}%")
        ->orWhere('lastname', 'LIKE', "%{$search}%")

        ->count();
    }

    $data = [];
    if (!empty($users)) {
      $data = [];
      foreach ($users as $user) {
        $show = '/show/1'; //route('app-user-list.show', $user->id);
        $edit = '/edit'; //route('app-user-list.edit', $user->id);
        $delete = '/delete'; //route('app-user-list.delete', $user->id);

        $nestedData['id'] = $user->id;
        $nestedData['username'] = $user->username;
        $nestedData['email'] = $user->email;
        $nestedData['firstname'] = $user->firstname;
        $nestedData['lastname'] = $user->lastname;
        $nestedData['company'] = $user->company;
        $nestedData['additional_emails'] = 'test';
        $nestedData['date_added'] = 'created_at';
        $nestedData['status'] = $user->status;
        $nestedData['actions'] = 'actions';

        $data[] = $nestedData;
      }

      $json_data = [
        'draw' => intval($request->input('draw')),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
        'data' => $users,
      ];

      echo json_encode($json_data);

      return;
    }
  }
  public function create()
  {
    $companies = Company::all();
    $roles = Role::all();
    return view('add-user', compact('companies', 'roles'));
  }
  public function store(Request $request)
  {
    if (isset($request->user_id) && $request->user_id != '') {
      $id = $request->user_id;
    } else {
      $id = null;
    }
    // Validate the request
    $request->validate([
      'username' => 'required|min:3|unique:users,username,' . ($id ?? 'NULL') . ',id',
      'email' => 'required|email|unique:users,email,' . ($id ?? 'NULL') . ',id',
      'firstName' => 'required',
      'lastName' => 'required',
      'role_id' => 'required',

      'password' => $id ? 'nullable|min:6' : 'required|min:6',
    ]);

    // Create a new user
    if ($id) {
      $user = User::find($id);
      if (!$user) {
        return response()->json(['status' => false, 'message' => 'User not found.']);
      }
      //delete all additional emails

      if (isset($request->password) && $request->password != '') {
        $user->password = bcrypt($request->password); // Hash the password
      }

      $msg = 'User updated successfully.';
    } else {
      $user = new User();
      $msg = 'User created successfully.';
      $user->password = bcrypt($request->password);
    }

    $user->username = $request->username;
    $user->email = $request->email;
    $user->firstname = $request->firstName;
    $user->lastname = $request->lastName;
    $user->company_id = 10;
    $user->role_id = $request->role_id;

    $user->save();
    // Create additional emails

    return response()->json(['status' => true, 'message' => $msg]);
  }
  public function edit($id)
  {
    $user = User::where('id', $id)
      ->with('additionalEmails')
      ->first();
    $companies = Company::all();
    $roles = Role::all();
    return view('add-user', compact('user', 'companies', 'id', 'roles'));
  }
  public function update_status($id)
  {
    $user = User::find($id);
    if ($user) {
      if ($user->status == 'A') {
        $user->status = 'D';
      } else {
        $user->status = 'A';
      }
      $user->save();
      return response()->json(['status' => true, 'message' => 'User status updated successfully.']);
    } else {
      return response()->json(['status' => false, 'message' => 'User not found.']);
    }
  }
  public function changepassword(Request $request)
  {
    $request->validate([
      'current_password' => 'required',
      'password' => 'required|min:6|same:confirm_password',
      'confirm_password' => 'required',
    ]);

    $user = User::find(Auth::user()->id);
    if (!$user) {
      return response()->json(['status' => false, 'message' => 'User not found.']);
    }

    if (!password_verify($request->old_password, $user->password)) {
      return response()->json(['status' => false, 'message' => 'Old password is incorrect.']);
    }

    $user->password = bcrypt($request->new_password);
    $user->save();

    return response()->json(['status' => true, 'message' => 'Password changed successfully.']);
  }

  public function destroy($id)
  {
    $user = User::find($id);
    if ($user) {
      $user->delete();
      return response()->json(['status' => true, 'message' => 'User deleted successfully.']);
    } else {
      return response()->json(['status' => false, 'message' => 'User not found.']);
    }
  }
}
