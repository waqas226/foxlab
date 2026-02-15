
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
     
    }

    </style>
<div id="footer">
    <img src="https://helpdesk.talerman.com/images/todo.png" style="width: 40px"  >
</div>
					<table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 110 30px 50px;">
						<tr>
						  <td colspan="2">
						  	  <table width="100%">
							  	<tr>
								  <td width="100%" align="center"><div class="headerlogo" style="margin:0px 0px 20px 0px;display: -ms-flexbox; display: -webkit-flex;display: flex;align-items: center;-ms-align-items: center;-webkit-align-items: center;justify-content: center;-ms-justify-content: center;-webkit-justify-content: center; flex-wrap:wrap; -webkit-flex-wrap:wrap; -ms-flex-wrap:wrap;"><img src="https://helpdesk.talerman.com/./images/pdflogo.png" width="300"  /></td>
								</tr>
							  </table>
						  </td>
					  </tr>
					  <tr>
						<tr><td width="40%"><b>Company:</b></td><td width="60%">{{$company->name}}</td></tr>
						<tr><td><b>Created on:</b></td><td width="60%">{{date('Y-m-d h:i:s A')}}</td></tr>
						<tr><td height="0" colspan="2"><div style="width:100%; float:left; border-bottom:1px solid #000; margin:10px 0 15px;"></div></td></tr>
						<tr><td colspan="2" style="padding-bottom:20px;"><span style="display: inline-block;padding-left: 20px;"><b>Upcoming todo list :</b></span><br/><ul id="next_visit_todo_list"><li style="line-height:30px;list-style-type: none;">
	<div style="display:flex;">
	<span style="display: inline-block;margin-right: 10px;width: 100px;font-weight: 600;">Due Date</span><span style="width:325px;font-weight: 600;">Description</span></div></li><br/>
	@foreach($todos as $todo)
	<li style="line-height:20px; 
	color:{{(strtotime($todo->toodo_date) >= strtotime(date('Y-m-d'))?strtotime($todo->toodo_date) == strtotime(date('Y-m-d'))?'Black':'Blue':'Red')}};">
		<div style="display:flex;">
			<span style="display: inline-block;margin-right: 10px;width: 100px;">{{$todo->toodo_date}}</span>
		<span style="width:450px">{{$todo->next_visit_todo}}</span></div>
	</li>
	@endforeach
								  </ul></td></tr></table><script type="text/javascript">window.print(); jQuery(document).ready(function() { setTimeout(function() { window.close();}, 100);});</script></body></html>
	<!-- <div style="page-break-after: always"></div> -->
</div>
