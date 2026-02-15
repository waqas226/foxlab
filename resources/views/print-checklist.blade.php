
<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

				<table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding:10px 110 30px 50px;">

					  <tr>
						<tr><td width="40%"><b>Title:</b></td><td width="60%">{{$checklist->title}}</td></tr>
						<tr><td><b>Created on:</b></td><td width="60%">{{$checklist->created_at}}</td></tr>
						<tr><td height="0" colspan="2"><div style="width:100%; float:left; border-bottom:1px solid #000; margin:10px 0 15px;"></div></td></tr>
						<tr><td colspan="2" style="padding-bottom:20px;"><span style="display: inline-block;padding-left: 20px;"><b>Tasks List :</b></span><br/>
						
</td>
</tr>

</table>
<table class="task-table" width="100%" cellpadding="2" cellspacing="0" style="padding:10px 110 30px 50px; text-align: left; ">
    <thead >
      <tr>
        <th>Task ID</th>
        <th>Title</th>
        
        <th>Notes</th>
      </tr>
    </thead>
    <tbody>
        <?php $c = 1; ?>
      @foreach($checklist->tasks as $task)
        <tr>
            
          <td>{{ $c++ }}</td>
          <td>{{ $task->title }}</td>
          <td></td>
         
        </tr>
      @endforeach
    </tbody>
  </table>

<script type="text/javascript">window.print(); jQuery(document).ready(function() { setTimeout(function() { window.close();}, 100);});</script></body></html>
<!-- <div style="page-break-after: always"></div> -->
</div>
