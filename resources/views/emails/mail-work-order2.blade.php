<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Work Order</title>
    <style>
        body {
            background-color: #FFFFFF;
            margin: 0px;
            font-size: 17px;
            font-family: Arial, sans-serif;
        }
        .headerlogo {
            margin: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        .task-table th, .task-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .divider {
            width: 100%;
            float: left;
           border: 2px solid #333;
            margin: 10px 0 15px;
        }
        .footer {
            position: absolute;
            right: 5px;
            bottom: 10%;
            display: flex;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Footer (will appear on every page if dompdf is configured for it) -->
    <div class="footer">
        <img src="https://dev.foxlablogistics.com/assets/img/workorder.png" style="height: 500px"  >
    </div>
    <!-- Work order details -->
    <div id="content" style="width: 100%; padding: 20px;">
        <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 0 30px 0;">
            <tr>
                <td colspan="2">
                    <table width="100%" style="margin-bottom:20px">
                       
                        <tr>
                            <td width="100%" align="center">
                                <div class="headerlogo" style="margin:0px 0px 20px 0px;">
                                    <img src="https://dev.foxlablogistics.com/assets/img/main-logo.png" style="width: 90%;" />
                                </div>
                            </td>
                        </tr>
                         <tr>
                            <td width="100%" align="center">
                                <div class="headerlogo">
                                    <h5>
                                    Phone: 760 525-7298
<br>https//: LaboratoryEquipmentServices.com 

                                    </h5>
                                </div>
                            </td>
                        </tr>
                          
                    </table>
                </td>
            </tr>
            <tr>
                <td><b>Company:</b> {{$workOrder->customer->company}}</td>
                <td><b>Job Date:</b> {{$workOrder->created_at->format('m/d/Y')}}</td>
               
            </tr>
            <tr>
                <td><b>Address:</b> {{$workOrder->customer->address}}</td>
                <td><b>WO #:</b> {{$workOrder->qb}}</td>
               
            </tr>
           
            <tr>
                <td><b>Contact:</b> {{$workOrder->customer->primary_contact}}</td>
                <td><b>WO Type:</b> {{$workOrder->type}}</td>
              
            </tr>
            <tr>
                <td><b>Contact Phone:</b> {{$workOrder->customer->primary_phone}}</td>
                <td><b>WO Status:</b> {{$workOrder->status}}</td>
            </tr>
            <tr>
                <td><b>Contact 2:</b> {{$workOrder->customer->secondary_contact}}</td>
                <td><b> PO #:</b> {{$workOrder->client_po ?? 'N/A'}}</td>
            </tr>
           
            <tr>
                <td><b>Contact 2 Phone:</b> {{$workOrder->customer->secondary_phone}}</td>
                <td></td>
            </tr>
            <tr><td height="0" colspan="2"><hr class="divider"></td></tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:0px 0 30px 0;    ">  
          <tr>
                            <td width="100%" align="">
                                <div    style=" margin-bottom:5px;">
                        Hello @if($customertype==2) 
                        {{$workOrder->customer->secondary_contact}},
                        @else
                        {{$workOrder->customer->primary_contact}},
                        @endif
                        <br>
                        <br>
Please find attached the field service documents for the instruments that our technicians serviced. 
<br>
<br>
Please reply or call if you have any questions or concerns.
  </div>
                            </td>
                        </tr>
                        </table>
                 <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:0px 0 30px 0;">
                        <!--<tr>-->
                        <!--    <td width="100%" >-->
                        <!--        <div class="headerlogo" style="margin:0px 0px 20px 0px;">-->
                        <!--            <img src="https://dev.foxlablogistics.com/assets/img/logo-2.png" style="width:40%"  />-->
                        <!--        </div>-->
                        <!--    </td>-->
                        <!--</tr>-->
         <tr>
                            <td width="100%">
                                <div class="headerlogo">
                                    <p><br>
                                        Beverly Fox,

CFO
<br>

 bev@foxlablogistics.com


                                    </p>
                                </div>
                            </td>
                        </tr>
        </table>        
        <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 0 30px 0;">
            <tr>
                <td width="100%">Customer Signature:<br>
                    @if($workOrder->signature)
                        <img src="{{$workOrder->signature}}" alt="Signature" style="max-height: 80px;    margin-left:20px;">
                    @else
                        <span>No Signature</span>
                    @endif
                </td>
            </tr>
        </table>
      
        
        @foreach($devices as $device)
          <div class="page-break"></div>
            <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 0 30px 0;">
            <tr>
                        <td>
                          <b>Company:</b> {{$workOrder->customer->company}}
                        </td>
                        <td>
                          <b>Date:</b> {{$workOrder->created_at->format('m/d/Y')}}
                        </td>
                      </tr>  
            <tr >
                    <td >
                        <b>WO Line #:</b> {{$device->pivot->sort_order ?? ''}} 
                      
                    </td>
                    <td><b>Asset #</b> {{$device->asset}}</td>
                </tr>
                <tr>
                    <td><b>Serial Number:</b> {{$device->sn}}</td>
                    <td><b>WO #</b> {{$workOrder->qb}}</td>
                </tr>
                <tr>
                    <td><b>Make:</b> {{$device->make}}</td>
                    <td><b>PO #:</b> {{$workOrder->client_po ?? ''}}</td>
                </tr>
                <tr>
                    <td><b>Model:</b> {{$device->model}}</td>
                    <td></td>
                </tr>
                <tr><td height="0" colspan="2"><div class="divider"></div></td></tr>
                <tr>
                    <td colspan="2" style="padding-bottom:20px;"><span style="display: inline-block;padding-left: 20px;"><b>Check List :</b></span><br/></td>
                </tr>
            </table>
            <table class="task-table" width="100%" cellpadding="2" cellspacing="0" style="padding:10px 0 30px 0; text-align: left;">
                @if($workOrder->type=='Repair')
                    <thead>
                        <tr>
                            <th style=""></th>
                            <th>Description / Part Number</th>
                            <th style="">Quantity</th>
                            <th style="width:45%">Notes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($device->checklistTasks as $checklist)
                            <tr>
                                <td><b>{{ $checklist['title'] }}</b></td>
                                <td>{{ $checklist['description'] }}</td>
                                <td>{{ $checklist['quantity'] }}</td>
                                <td>{{ $checklist['notes'] }}</td>
                                <td>@if($checklist['completed'])
                                    <span style="color: green; font-weight: bold;">&#10003;</span>
                                @endif</td>
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <thead>
                        <tr>
                            <th style=";">Task ID</th>
                            <th>Title</th>
                            <th style="min-width:5%"></th>
                            <th style="width: 45%;">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($device->checklist)
                            <?php $c = 1; ?>
                            @foreach($device->checklist->tasks as $task)
                                <tr>
                                    <td>{{ $c++ }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>@if($task->workOrdersCompleted && $task->workOrdersCompleted->completed)
                                        <span style="color: green; font-weight: bold;">&#10003;</span>
                                    @endif</td>
                                    <td>{{ ($task->workOrdersCompleted && $task->workOrdersCompleted->completed) ? $task->workOrdersCompleted->notes : '' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                @endif
            </table>
           
        @endforeach
    </div>
</body>
</html>
