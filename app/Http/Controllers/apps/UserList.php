<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdditionalEmail;
use App\Models\Company;

class UserList extends Controller
{
  public function index()
  {
    $companies = Company::all();
    return view('content.apps.app-user-list', compact('companies'));
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
    return view('add-user', compact('companies'));
  }
  public function store(Request $request)
  {
    // Validate the request
    $request->validate([
      'username' => 'required|min:3|unique:users,username',
      'email' => 'required|email|unique:users,email',
      'firstName' => 'required',
      'lastName' => 'required',
      'company' => 'required|exists:companies,id',
      'password' => 'required|min:6',
    ]);

    // Create a new user
    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->firstname = $request->firstName;
    $user->lastname = $request->lastName;
    $user->company_id = $request->company_id;
    $user->password = bcrypt($request->password); // Hash the password
    $user->save();

    // Create additional emails
    if ($request->has('additional_emails')) {
      foreach ($request->additional_emails as $email) {
        $additionalEmail = new AdditionalEmail();
        $additionalEmail->user_id = $user->id;
        $additionalEmail->email = $email;
        $additionalEmail->save();
      }
    }

    return response()->json(['status' => true, 'message' => 'User added successfully.']);
  }
  public function edit($id)
  {
    $user = User::find($id);
    $companies = Company::all();
    return view('add-user', compact('user', 'companies'));
  }
}
