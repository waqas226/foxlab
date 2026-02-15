
<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title></title>
				<link rel="stylesheet" type="text/css" href="https://helpdesk.talerman.com/css/style.css" />
				<style type="text/css" media="print">
					@page 
					{
						size: auto;   /* auto is the current printer page size */
						margin: 0mm;  /* this affects the margin in the printer settings */
					}
			
					body 
					{
						background-color:#FFFFFF; 
						font-size:12px;
						font-family:"Times New Roman", Times, serif;
						margin: 0px;  /* the margin on the content before printing */
				   }
				</style>
				
			</head>
			<body>
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

    </style>
<div id="header">
    <img src="https://helpdesk.talerman.com/images/note.png" style="width: 40px"  >
</div>
			
			<script type="text/javascript">window.print(); jQuery(document).ready(function() { setTimeout(function() { window.close();}, 100);});</script><script type="text/javascript">
  function printPage()
  {      window.location.href = window.location.href+'&event=1&print=1';
  }
  </script></body></html><div style="width:90%; max-width:600px; float:left; padding:20px 3%;"><tr>
					  <td colspan="2" height="0">
					  	<table style="padding:30px 30px 0px 30px;" width="600" cellspacing="2" cellpadding="2" border="0">
						  	<tr>
							  <td width="20%" align="center"><div class="headerlogo" style="display: -ms-flexbox; display: -webkit-flex;display: flex;align-items: center;-ms-align-items: center;-webkit-align-items: center;justify-content: center;-ms-justify-content: center;-webkit-justify-content: center;"><img src="https://helpdesk.talerman.com/images/logo.jpg" style="height:140px;" /><div class="headerlogotext" style="color: #6ab0e8;font-size: 30px;font-weight: 600;padding-left: 9px;line-height: 30px;">Talerman<br><span style="color: #626262;font-size: 28px;line-height: 28px;">HelpDesk</span></div></td>
							</tr>
						  </table>
					  </td>
				  </tr>
				  <tr><td height="0" colspan="2"><div style="width:100%; float:left; border-bottom:1px solid #000; margin:10px 0 15px;"></div></td></tr><br/>
                  <h1 style="text-align:center"><strong>{{$note->title}}</strong></h1>
                 <?= $note->items ?>
</div>