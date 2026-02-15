
<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title></title>
					<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
                    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/fontawesome.css')}}">
                   
					<style type="text/css" media="print">
						@page {
									size: auto;
									/* auto is the current printer page size */
									margin: 0mm;
									/* this affects the margin in the printer settings */
									 margin-top: 1cm;
								
								}
								
								@media print {
								#content {
									position: absolute;
									z-index: 1;
								}
								
								#bg-text {
									color: rgba(97,167,221,0.51);
									font-size: 50px;
									transform: rotate(90deg);
									-webkit-transform: rotate(90deg);
									position: absolute;
									right: 0px;
									top: 30%
							}
					}
								
								body {
									background-color: #FFFFFF;
									margin: 0px;
									/* the margin on the content before printing */
								}
								
								
							 
					</style>
					
				</head>
				<body style="font-size: 17px;font-family: Arial" >
				<style>
				  @media print {
    div#footer {
        position:absolute;
        right:25px;
        bottom: 10%;
        display: flex !important;
    }
    }
    div#footer {
        display: none;
    }

    </style>
<div id="footer">
    <img src="https://dev.foxlablogistics.com/assets/img/workorder.png" style="height: 600px"  >
</div>
<style>
    @media print {
    div#header {
        position:fixed;
        right:30px;
        bottom: 10%;
        display: flex !important;
    }
    }
    div#header {
        display: none;
    }

	.task-table th ,.task-table td {
									
									border: 1px solid #333;
									padding: 8px;
									text-align: left;
															  }

    </style> 
<!-- work order drtails -->
<div id="content" style="width: 100%; padding: 20px;">
  
                              </div>
        <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 110 30px 50px;">
                               
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
                <td><b> Date:</b> {{$workOrder->created_at->format('m/d/Y')}}</td>
               
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
            <tr><td height="0" colspan="2"><div style="width:100%; float:left; border-bottom:1px solid #000; margin:10px 0 15px;"></div></td></tr>
						
           
        </table>

       

        <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:0px 0 30px 10px;    ">  
          <tr>
                            <td width="100%" align="">
                                <div    style=" margin-bottom:5px;">
                       
                     Hello   {{$workOrder->customer->primary_contact}},
                        
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
                 <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:0px 0 30px 10px;">
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
        <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 0 30px 10px;">
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
        <div style="page-break-after: always"></div>
    @foreach($devices as $device)

				<table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 110 30px 50px;">

					 
                      <tr>
                        <td>
                          <b>Company:</b> {{$workOrder->customer->company}}
                        </td>
                        <td>
                          <b>Date:</b> {{$workOrder->created_at->format('m/d/Y')}}
                        </td>
                      </tr>
                      <tr>
                    <td>
                        <b>WO Line #:</b> {{$device->pivot->sort_order ?? ''}} 
                    </td>
                    <td> 
                      
                        <b>Asset #</b> {{$device->asset}}
                    </td>
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
					<tr><td height="0" colspan="2"><div style="width:100%; float:left; border-bottom:1px solid #000; margin:10px 0 15px;"></div></td></tr>
						
						<tr><td colspan="2" style="padding-bottom:20px;"><span style="display: inline-block;padding-left: 20px;"><b>Check List :</b></span><br/>
						
</td>
</tr>

</table>
<table class="task-table" width="100%" cellpadding="2" cellspacing="0" style="padding:10px 110 30px 50px; text-align: left; ">
@if($workOrder->type=='Repair')
<thead >
      <tr>
        <th style="width: 3%;"></th>
        <th>Description / Part Number</th>
        <th style="width: 3%;">Quantity</th>
        <th style="width: 50%;">Notes</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
     @foreach($device->checklistTasks as $checklist)
        <tr>
          <td>{{ $checklist['title'] }}</td>
          <td>{{ $checklist['description'] }}</td>
          <td>{{ $checklist['quantity'] }}</td>
          <td>{{ $checklist['notes'] }}</td>
          <td>@if($checklist['completed'])
          <i class="fa fa-check text-success"></i>
      
        @endif
        </td>
        </tr>
      @endforeach
    </tbody>

@else

<thead >
      <tr>
        <th style="width: 3%;">Task ID</th>
        <th>Title</th>
        <th style="width: 3%;"></th>
        <th style="width: 50%;">Notes</th>
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
          <i class="fa fa-check text-success"></i>
      
        @endif
        </td>
            <td>{{ ($task->workOrdersCompleted && $task->workOrdersCompleted->completed) ? $task->workOrdersCompleted->notes : '' }}</td>
         
        </tr>
      @endforeach
      @endif
    </tbody>
@endif
  </table>
  <div style="page-break-after: always"></div>
  @endforeach
<script type="text/javascript">window.print(); jQuery(document).ready(function() { setTimeout(function() { window.close();}, 100);});</script></body></html>
<!-- <div style="page-break-after: always"></div> -->
</div>
