<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Task;
use App\Models\TaskCompleted;
use App\Models\Checklist;
use App\Models\RepairTask;
use App\Models\SiteConstant;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use App\Mail\WorkOrderMail;
use Illuminate\Support\Facades\View;

class WorkController extends Controller
{
  public function index()
  {
    $workOrder = WorkOrder::with('devices')->get();

    $customers = Customer::all();
    // Logic to display the work dashboard or list of works
    return view('manage-work-orders', compact('workOrder', 'customers'));
  }
  public function show(Request $request)
  {
    $columns = ['company', 'company', 'address', 'devices', 'qb', 'type', 'created_at', 'status'];

    $query = WorkOrder::with('devices')->with('customer');
    $totalData = $query->count();
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $search = $request->input('search.value');

    // $workOrder = WorkOrder::with('devices')
    // ->with('customer')
    // ->get();

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('qb', 'like', "%{$search}%")
          ->orWhere('created_at', 'like', "%{$search}%")
          ->orWhereHas('customer', function ($q) use ($search) {
            $q->where('company', 'like', "%{$search}%")->orWhere('address', 'like', "%{$search}%");
          });
      });
    }

    if ($request->input('status')) {
      $status = $request->input('status');
      if ($status == 'All') {
      } elseif ($status == 'Open') {
        $query->where(function ($q) {
          $q->where('work_orders.status', 'Open')->orWhere('work_orders.status', 'Pending');
        });
      } else {
        $query->where('work_orders.status', $status);
      }
    }
    if ($request->input('company_id')) {
      $query->where('customer_id', $request->input('company_id'));
    }
    if ($order == 'company') {
      //order by customer company name
      $workOrder = $query
        ->join('customers', 'work_orders.customer_id', '=', 'customers.id')
        ->orderBy('customers.company', $dir)
        ->with('customer')
        ->select('work_orders.*');
    } elseif ($order == 'address') {
      //order by customer company name
      // Order by the related customer's address directly in the query
      $workOrder = $query
        ->join('customers', 'work_orders.customer_id', '=', 'customers.id')
        ->orderBy('customers.address', $dir)
        ->with('customer')
        ->select('work_orders.*');
    } elseif ($order == 'devices') {
      //order by total number of devices
      $workOrder = $query->withCount('devices')->orderBy('devices_count', $dir);
    } else {
      $workOrder = $query->orderBy($order, $dir);
    }

    return response()->json([
      'data' => $workOrder->get(),
      'draw' => $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $workOrder->count(),
    ]);
  }
  public function create($id = null)
  {
    $customer = Customer::find($id);
    $devices = Device::where('company_id', $id)
      ->orderBy('next_pm', 'asc')
      ->get();
    return view('work-orders-create', compact('id', 'devices', 'customer'));
  }
  public function edit($id)
  {
    $workOrder = WorkOrder::find($id);
    $selectedDevices = $workOrder->devices->pluck('id');
    // Load devices with pivot sort_order
    $workOrder->load('devices');
    $devices = Device::where('company_id', $workOrder->customer_id)
      ->orderBy('next_pm', 'asc')
      ->get();
    $customer = $workOrder->customer;
    return view('work-orders-edit', compact('workOrder', 'devices', 'customer', 'selectedDevices'));
  }
  public function store(Request $request)
  {
    $validated = $request->validate([
      'customer_id' => 'required|exists:customers,id',
      'qb' => 'required|unique:work_orders,qb',
      'type' => 'required',
      'devices' => 'required|array',
      'devices.*' => 'exists:devices,id',
      'device_sort_orders' => 'nullable|array',
    ]);

    $workOrder = WorkOrder::create([
      'customer_id' => $validated['customer_id'],
      'qb' => $validated['qb'],
      'client_po' => $request->client_po ?? null,
      'type' => $validated['type'],
      'notes' => $request->notes ?? '',
    ]);

    // Attach selected devices with sort order
    $deviceData = [];
    $sortOrders = $request->device_sort_orders ?? [];
    
    foreach ($validated['devices'] as $deviceId) {
      $deviceData[$deviceId] = [
        'sort_order' => isset($sortOrders[$deviceId]) ? (int)$sortOrders[$deviceId] : 0
      ];
    }
    
    $workOrder->devices()->attach($deviceData);

    return response()->json(['status' => true, 'message' => 'Work order created with devices.']);
  }
  public function update(Request $request)
  {
    $validated = $request->validate([
      'work_order_id' => 'required|exists:work_orders,id',
      'devices' => 'required|array',
      'devices.*' => 'exists:devices,id',
      'qb' => 'required|unique:work_orders,qb,' . $request->work_order_id,
      'type' => 'required',
      'notes' => 'nullable',
      'device_sort_orders' => 'nullable|array',
    ]);
    $workOrder = WorkOrder::find($request->work_order_id);
    $workOrder->qb = $request->qb;
    $workOrder->client_po = $request->client_po ?? null;
    $workOrder->type = $request->type;
    $workOrder->notes = $request->notes ?? '';
    $workOrder->save();
    
    // Sync devices with sort orders
    $deviceData = [];
    $sortOrders = $request->device_sort_orders ?? [];
    
    foreach ($validated['devices'] as $deviceId) {
      $deviceData[$deviceId] = [
        'sort_order' => isset($sortOrders[$deviceId]) ? (int)$sortOrders[$deviceId] : 0
      ];
    }
    
    $workOrder->devices()->sync($deviceData);
    return response()->json(['status' => true, 'message' => 'Work order updated with devices.']);
  }
  public function view($id = null)
  {
    $work = WorkOrder::with('devices')
      ->with('customer')
      ->findOrFail($id);
    $type = $work->type;
    $devices = $work->devices;
    $customer = $work->customer;

    $devices->map(function ($device) use ($id, $type) {
      if ($type == 'Repair') {
        $labortype = ['Labor', 'Parts', 'Parts', 'Parts', 'Travel'];
        $tasks = RepairTask::where('work_order_id', $id)
          ->where('device_id', $device->id)
          ->get();

        if ($tasks->count() == 0) {
          foreach ($labortype as $type) {
            RepairTask::create([
              'work_order_id' => $id,
              'device_id' => $device->id,
              'title' => $type,
              'description' => '',
              'quantity' => '',
              'notes' => '',
            ]);
          }
        }

        $device->tasks = $tasks;
        $device->tasks_total = $tasks->count();
        if ($device->tasks_total > 0) {
          $device->tasks_completed = $tasks->where('completed', 1)->count();
          $device->completed = $device->tasks_completed == $device->tasks_total;
        } else {
          $device->completed = false;
        }

        return $device;
      } else {
        $checklist = Checklist::find($device->checklist_id);
        if (!$checklist) {
          $device->checklist = null;
          return $device;
        }
        $workid = $id;
        $deviceid = $device->id;
        $taskslists = Task::with([
          'workOrdersCompleted' => function ($query) use ($workid, $deviceid) {
            $query->where('work_order_id', $workid);
            $query->where('device_id', $deviceid);
          },
        ])
          ->where('checklist_id', $checklist->id)
          ->get();

        $device->tasks_total = $taskslists->count();
        $tasks_completed = 0;

        foreach ($taskslists as $task) {
          if ($task->workOrdersCompleted->first() && $task->workOrdersCompleted->first()->completed == 1) {
            $tasks_completed++;
          }
        }
        $device->tasks_completed = $tasks_completed;
        if ($device->tasks_completed == $device->tasks_total) {
          $device->completed = true;
        } else {
          $device->completed = false;
        }
        return $device;
      }
    });
    //return $devices;
    return view('work-order-active', compact('work', 'devices', 'customer'));
  }
  public function viewchecklist($work_order_id, $device_id = null)
  {
    $work = WorkOrder::find($work_order_id);
    $device = Device::with('customer')->find($device_id);
    $checklist = Checklist::find($device->checklist_id);

    if ($work->type == 'Repair') {
      $labortype = ['Labor', 'Parts', 'Parts', 'Parts', 'Travel'];
      $tasks = RepairTask::where('work_order_id', $work_order_id)
        ->where('device_id', $device_id)
        ->get();

      if ($tasks->count() == 0) {
        foreach ($labortype as $type) {
          RepairTask::create([
            'work_order_id' => $work_order_id,
            'device_id' => $device_id,
            'title' => $type,
            'description' => '',
            'quantity' => '',
            'notes' => '',
          ]);
        }
      }
      $tasks = RepairTask::where('work_order_id', $work_order_id)
        ->where('device_id', $device_id)
        ->get();

      $checklistTasks = $tasks->map(function ($task) {
        return [
          'id' => $task->id,
          'title' => $task->title,
          'description' => $task->description,
          'quantity' => $task->quantity,
          'notes' => $task->notes,
          'completed' => $task->completed,
        ];
      });
    } elseif (!$checklist) {
      return redirect()
        ->back()
        ->with('error', 'Checklist not found');
    } else {
      $tasks = Task::with([
        'workOrdersCompleted' => function ($query) use ($work_order_id, $device_id) {
          $query->where('work_order_id', $work_order_id);
          $query->where('device_id', $device_id);
        },
      ])
        ->where('checklist_id', $checklist->id)
        ->get();

      $checklistTasks = $tasks->map(function ($task) {
        $completed = $task->workOrdersCompleted->first();
        return [
          'id' => $task->id,
          'title' => $task->title,
          'checklist_id' => $task->checklist_id,
          'completed' => (bool) optional($completed)->completed,
          'notes' => optional($completed)->notes,
        ];
      });
    }

    // return $checklistTasks;
    return view(
      'work-order-checklist',
      compact('checklist', 'device', 'checklistTasks', 'work_order_id', 'device_id', 'work')
    );
  }
  public function viewChecklistReadonly(Request $request, $work_order_id, $device_id = null)
  {
    $work = WorkOrder::findOrFail($work_order_id);
    $device = Device::with('customer')->findOrFail($device_id);
    $checklist = Checklist::find($device->checklist_id);

    if ($work->type == 'Repair') {
      $tasks = RepairTask::where('work_order_id', $work_order_id)
        ->where('device_id', $device_id)
        ->get();

      $checklistTasks = $tasks->map(function ($task) {
        return [
          'id' => $task->id,
          'title' => $task->title,
          'description' => $task->description,
          'quantity' => $task->quantity,
          'notes' => $task->notes,
          'completed' => $task->completed,
        ];
      });
    } elseif (!$checklist) {
      return redirect()
        ->back()
        ->with('error', 'Checklist not found');
    } else {
      $tasks = Task::with([
        'workOrdersCompleted' => function ($query) use ($work_order_id, $device_id) {
          $query->where('work_order_id', $work_order_id);
          $query->where('device_id', $device_id);
        },
      ])
        ->where('checklist_id', $checklist->id)
        ->get();

      $checklistTasks = $tasks->map(function ($task) {
        $completed = $task->workOrdersCompleted->first();
        return [
          'id' => $task->id,
          'title' => $task->title,
          'checklist_id' => $task->checklist_id,
          'completed' => (bool) optional($completed)->completed,
          'notes' => optional($completed)->notes,
        ];
      });
    }

    $backUrl = $request->query('back', url()->previous());

    return view(
      'work-order-checklist-readonly',
      compact('checklist', 'device', 'checklistTasks', 'work_order_id', 'device_id', 'work', 'backUrl')
    );
  }
  public function deviceHistory($work_order_id, $device_id)
  {
    $work = WorkOrder::findOrFail($work_order_id);
    $device = Device::findOrFail($device_id);

    $history = WorkOrder::whereHas('devices', function ($query) use ($device_id) {
      $query->where('devices.id', $device_id);
    })
      ->orderBy('created_at', 'desc')
      ->get();

    $backUrl = route('work-orders.view', ['id' => $work->id]);

    return view('work-order-device-history', compact('work', 'device', 'history', 'backUrl'));
  }
  public function deviceHistoryByDevice($device_id)
  {
    $device = Device::findOrFail($device_id);

    $history = WorkOrder::whereHas('devices', function ($query) use ($device_id) {
      $query->where('devices.id', $device_id);
    })
      ->orderBy('created_at', 'desc')
      ->get();

    $backUrl = route('manage-devices');

    return view('work-order-device-history', compact('device', 'history', 'backUrl'));
  }
  public function updatechecklist(Request $request)
  {
    $validated = $request->validate([
      'tasks' => 'required|array',
      'completed' => 'required|array',
      'notes' => 'required|array',
    ]);

    $workOrder = WorkOrder::find($request->work_order_id);
    $device = Device::find($request->device_id);

    if ($workOrder->type == 'Repair') {
      $tasks = RepairTask::where('work_order_id', $request->work_order_id)
        ->where('device_id', $device->id)
        ->get();

      foreach ($tasks as $key => $task) {
        $task->completed = $request->completed[$key] ?? 0;
        $task->notes = $request->notes[$key] ?? '';
        $task->description = $request->description[$key] ?? '';
        $task->quantity = $request->quantity[$key] ?? '';
        $task->save();
      }
    } else {
      $checklist = Checklist::find($device->checklist_id);
      $tasks = $request->tasks;
      $completed = $request->completed;
      $notes = $request->notes;

      foreach ($tasks as $key => $task) {
        TaskCompleted::updateOrCreate(
          [
            'work_order_id' => $request->work_order_id,
            'task_id' => $task,
            'device_id' => $request->device_id,
          ],
          [
            'notes' => $notes[$key],
            'completed' => $completed[$key] ?? 0,
          ]
        );
      }
    }
    return response()->json(['status' => true, 'message' => 'Checklist updated successfully']);
  }

  public function updatestatus(Request $request, $id)
  {
    $validated = $request->validate([
      'status' => 'required|in:Open,Closed,Pending,Archived',
    ]);
    $workOrder = WorkOrder::find($id);
    $workOrder->status = $request->status;
    $workOrder->save();
    return response()->json(['status' => true, 'message' => 'Work order status updated successfully']);
  }
  public function sign(Request $request)
  {
    $validated = $request->validate([
      'signature' => 'required',
      'id' => 'required',
    ]);

    $workOrder = WorkOrder::find($request->id);
    $workOrder->signature = $request->signature;
    $workOrder->status = 'Closed';
    $workOrder->save();

    return response()->json(['status' => true, 'message' => 'Signature saved successfully']);
  }

  public function print($id)
  {
    $workOrder = WorkOrder::with('devices')->find($id);

    $devices = $workOrder->devices;
    $type = $workOrder->type;
    $devices->map(function ($device) use ($id, $type) {
      if ($type == 'Repair') {
        $tasks = RepairTask::where('work_order_id', $id)
          ->where('device_id', $device->id)
          ->get();

        $checklistTasks = $tasks->map(function ($task) {
          return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'quantity' => $task->quantity,
            'notes' => $task->notes,
            'completed' => $task->completed,
          ];
        });
        $device->checklistTasks = $checklistTasks;
        return $device;
      } else {
        $checklist = Checklist::with('tasks')
          ->where('id', $device->checklist_id)
          ->first();
        if (!$checklist) {
          $device->checklist = null;
          return $device;
        }
        $deviceid = $device->id;
        $checklist->tasks->map(function ($task) use ($id, $deviceid) {
          $task->workOrdersCompleted = TaskCompleted::where('task_id', $task->id)
            ->where('work_order_id', $id)
            ->where('device_id', $deviceid)
            ->first();
          return $task;
        });
        $device->checklist = $checklist;
        return $device;
      }
    });
    // return $devices;
    $constant = SiteConstant::first();
    return view('print-work-order', compact('workOrder', 'devices', 'constant'));
  }
  public function delete($id)
  {
    $workOrder = WorkOrder::find($id);
    if (!$workOrder) {
      return response()->json(['status' => false, 'message' => 'Work order not found']);
    }
    $workOrder->delete();

    return response()->json(['status' => true, 'message' => 'Work order deleted successfully']);
  }

  public function sendMail(WorkOrderMail $mailService, $id, $customertype)
  {
    $workOrder = WorkOrder::with('devices')->find($id);

    $devices = $workOrder->devices;
    $type = $workOrder->type;
    $devices->map(function ($device) use ($id, $type) {
      if ($type == 'Repair') {
        $tasks = RepairTask::where('work_order_id', $id)
          ->where('device_id', $device->id)
          ->get();

        $checklistTasks = $tasks->map(function ($task) {
          return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'quantity' => $task->quantity,
            'notes' => $task->notes,
            'completed' => $task->completed,
          ];
        });
        $device->checklistTasks = $checklistTasks;
        return $device;
      } else {
        $checklist = Checklist::with('tasks')
          ->where('id', $device->checklist_id)
          ->first();
        if (!$checklist) {
          $device->checklist = null;
          return $device;
        }
        $deviceid = $device->id;
        $checklist->tasks->map(function ($task) use ($id, $deviceid) {
          $task->workOrdersCompleted = TaskCompleted::where('task_id', $task->id)
            ->where('work_order_id', $id)
            ->where('device_id', $deviceid)
            ->first();
          return $task;
        });
        $device->checklist = $checklist;
        return $device;
      }
    });
    // return $devices;
    $constant = SiteConstant::first();
if($id==46){
    // 1. Render Blade view into HTML
 
    $html = View::make('emails.mail-work-order2', compact('workOrder', 'devices', 'constant','customertype'))->render();
}else{
      $html = View::make('emails.mail-work-order2', compact('workOrder', 'devices', 'constant','customertype'))->render();
}
    // 2. Initialize mPDF
    $mpdf = new Mpdf([
      'mode' => 'utf-8',
      'margin_left' => 10,
      'margin_right' => 15,
      'margin_top' => 15,
      'margin_bottom' => 30,
      'margin_header' => 10,
      'margin_footer' => 10,
    ]);

    $mpdf->mirrorMargins = 0;

    // 3. Footer with image + page number
    // $imagePath = public_path('uploads/logo.png');
    // $imageBase64 = base64_encode(file_get_contents($imagePath));
    // $imageHtml = '<img src="data:image/png;base64,' . $imageBase64 . '" style="height: 100px">';

    $mpdf->SetHTMLFooter(
      '
            <table width="100%">
                <tr>
                    <td width="33%"></td>
                    <td width="33%"></td>
                    <td width="33%" style="text-align: right;">
                       <br><br>
                        <div style="float: right;">Page {PAGENO} of {nb}</div>
                    </td>
                </tr>
            </table>'
    );

    // 4. Write HTML to PDF
    $mpdf->WriteHTML($html);

    // 5. Get PDF as string

    

// if($id==46){
//     $mpdf->Output('filename.pdf', 'D');

  
//     return;
// }

    $pdf = $mpdf->Output('', 'S');
    // 6. Send via Microsoft Graph Mail Service

    $customer = Customer::find($workOrder->customer_id);
    $template = $constant->email_template;
    if ($customertype == 1) {
      $to = $customer->primary_email;
      $username = $customer->primary_contact;
    } else {
      $to = $customer->secondary_email;
      $username = $customer->secondary_contact;
    }
    $message = str_replace('{{user_name}}', $username, $template);
    $message = str_replace('{{work_order_id}}', $workOrder->qb, $message);
    $message = str_replace('{{work_order_date}}', $workOrder->created_at->format('m/d/Y'), $message);
    $message = str_replace('{{work_order_type}}', $workOrder->type, $message);
    $message = str_replace('{{work_order_devices}}', $devices->pluck('name')->implode(', '), $message);
    $message = str_replace('{{work_order_notes}}', $workOrder->notes, $message);

    $result = $mailService->sendMail(
      $to,
      ['bev@foxlablogistics.com','larry@foxlablogistics.com','waqas.jat226@gmail.com','abraham@talerman.com'],
      'Field Service Report WO#: ' . $workOrder->qb,
      $message,
      $pdf,
      'WO-' . $workOrder->qb . '.pdf'
    );

    if ($result['success']) {
      return response()->json(['status' => true, 'message' => 'Mail Sent successfully']);
    } else {
      return response()->json(['status' => false, 'message' => $result['message']]);
    }
  }
}
