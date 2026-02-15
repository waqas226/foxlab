
<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					 <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta charset="UTF-8">
						<title></title>
						<link rel="stylesheet" type="text/css" href="https://helpdesk.talerman.com/css/style.css" />
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
        right:30px;
        bottom: 10%;
        display: flex !important;
    }
    }
    div#footer {
        display: none;
    }

    </style>
<div id="footer">
    <img src="https://helpdesk.talerman.com/images/task-detail-(task).png" style="width: 40px"  >
</div>
					<table width="100%"  border="0" style="padding:5px 30px 30px 50px;padding-right: 110px;"><tr>
                              <td colspan="4">
                                  <table width="100%">
                                    <tr>
                                      <td width="100%" align="center"><div class="headerlogo" style="display: -ms-flexbox; display: -webkit-flex;display: flex;align-items: center;-ms-align-items: center;-webkit-align-items: center;justify-content: center;-ms-justify-content: center;-webkit-justify-content: center; flex-wrap:wrap; -webkit-flex-wrap:wrap; -ms-flex-wrap:wrap;">
                                      <img src="https://helpdesk.talerman.com/./images/pdflogo.png" width="300" alt=""  />
                                      </td>
                                    </tr>
                                  </table>
                              </td>
                          </tr>
                          <tr><td height="0" colspan="4"><div style="width:100%; float:left; border-bottom:0px solid #000; margin:10px 0 15px;"></div></td></tr><tr>
                            <td style="width: 20%; padding:5px 0;"><b>Company:</b></td> <td style="width:20%">{{$task->company->name}} </td>
                            <td style="width: 20%; padding:5px 0;"><b>Task ID:</b></td> <td>{{$task->id}}</td></tr>
                            <tr style="line-height:5px"><td>  <b>Priority:</b></td><td>{{$task->enumToDo}}</td>
                            <td style=" padding:5px 0;"><b>Device Affected:</b></td><td>{{$task->device_affected}}</td></tr>
                            <tr style="line-height:15px"><td style=" padding:5px 0;" ><b>Submitted By:</b></td><td>{{$task->user->firstname.' '.$task->user->lastname}}</td><td style=" padding:5px 0;"><b>Task Created on:</b></td><td style="white-space: nowrap;">{{date('Y-m-d h:m:s A',strtotime($task->created_at))}}</td></tr>
                            <tr style="line-height:15px"><td  style=" padding:px 0;"><b>Task Description:</b></td><td colspan="3">{{$task->short_desc}}</td></tr></tr>
                            <tr style="line-height:15px"><td colspan="4" height=""  style="padding: 30px 0px 5px 0px;"><div style="width:100%; float:left; border-bottom:1px solid #000;"></div></td></tr>
                            <tr>
								<td colspan="4"><span style="padding-left:20px;"><b>Detail:</b></span><br/>
								<div style="margin-top:20px;margin-left: 20px">
                                <?= $task->long_desc ?>
								</div>
								</td>
							</tr>
							<tr>
							<td colspan="4">
							@if($task->error_image)	
							<span style="padding-left:20px;"><b>Image:</b></span><br/>
								<div style="margin-top:20px;margin-left: 20px">
                               <img src="{{ asset('uploads/' . $task->error_image) }}" style="width: 100%; max-width: 600px; height: auto;" alt="Error Image" />
								</div>
								</td>
								@endif
							</tr>

							<!-- todo & renewal  -->
		@if($todoprint)
							<tr><td  colspan="3"><b style="padding-left:20px;"> Upcoming todo list :</b></td></tr>
                                             <tr><td  colspan="3">
                                             <ul id="next_visit_todo_list">
								<li style="line-height:20px;list-style-type: none;">
                                    <div style="display:flex;">
                                        <span style="display: inline-block;margin-right: 10px;width: 100px;font-weight: 600;">Due Date</span>
										<span style="width:325px;font-weight: 600;">Description</span>
									</div>
								</li> <br/>      
								@foreach($todos as $todo)                                       
                                <li style="line-height:20px; color:{{(strtotime($todo->toodo_date) >= strtotime(date('Y-m-d'))?strtotime($todo->toodo_date) == strtotime(date('Y-m-d'))?'Black':'Blue':'Red')}};">
									<div style="display:flex;">
										<span style="display: inline-block;margin-right: 10px;width: 100px;">{{$todo->toodo_date}}</span>
										<span style="width:325px">{{$todo->next_visit_todo}}</span>
									</div>
								</li>
								@endforeach	
								                                            
                                             </ul>
                                             </td>
                                             </tr><tr><td  colspan="3"><b  style="padding-left:20px;"> Upcoming renewals list :</b></td></tr>
									 <tr><td  colspan="3">
									 <ul id="next_visit_todo_list"><li style="line-height:20px;list-style-type: none;">
								<div style="display:flex;">
								<span style="display: inline-block;margin-right: 10px;width: 100px;font-weight: 600;">Due Date</span><span style="width:325px;font-weight: 600;">Description</span></div></li><br/>                                             
									@foreach($renewals as $renewal)                                       
								<li style="line-height:20px; color:{{(strtotime($renewal->renewal_date) >= strtotime(date('Y-m-d'))?strtotime($renewal->renewal_date) == strtotime(date('Y-m-d'))?'Black':'Blue':'Red')}};">
								
									<div style="display:flex;">
										<span style="display: inline-block;margin-right: 10px;width: 100px;">{{$renewal->renewal_date}}</span>
										<span style="width:325px">{{$renewal->title}}</span>
									</div>
								</li>       
								@endforeach                                    
									 </ul>
									 </td>
									 </tr>
									 @endif
</table>
<script type="text/javascript">window.print(); jQuery(document).ready(function() { setTimeout(function() { window.close();}, 100);});</script></body></html>