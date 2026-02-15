<html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                 <title>{{$company->name}} Records</title>
                <link rel="stylesheet" type="text/css" href="https://helpdesk.talerman.com/css/style.css" />
                <style type="text/css" media="print">
               
 table {
        page-break-before: auto;
        page-break-after: auto;
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }


                    @page {
                        size: auto;
                        margin: 0mm;
                        margin-top: 1cm;
                    }
                    @media print {
                    #pageFooter {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
        font-size: 12px;
        color: #000;
        margin:0;
        padding:0;
    }
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
                            top: 30%;
                        }
                    }
                    @page {
    size: auto;
    margin: 1cm;
    
    /* Automatically count and display page numbers */
    @bottom-center {
        content: "Page " counter(page) " of " counter(pages);
        font-size: 12px;
        color: #000;
    }
}

@media print {
   
    /* Ensuring page-break logic for tables */
    table {
        page-break-before: auto;
        page-break-after: auto;
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}

                    body {
                        background-color: #FFFFFF;
                        margin: 0px;
                    }
                </style>
            </head>
            <body style="font-size: 17px;font-family: Arial">
            <div id="footer">
                <img src="https://helpdesk.talerman.com/images/hard-detail-(hard).png" style="width: 40px; position: fixed; bottom: 10px; right: 15px;" />

            </div><div style="text-align: center; padding: 20px;">
                <div class="headerlogo">
                    <img src="https://helpdesk.talerman.com/./images/pdflogo.png" width="300" alt="Company Logo" />
                </div>
                <h4 style="display: flex;padding: 0 10%; color:#6ab0e8"><b style="margin-right:auto">Company: {{$company->name}}</b> <b>Printed On: {{date('Y-m-d h:i:s A')}}</b></h4>
                <h5></h5>
            </div><table width="90%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse; width:85%; ;margin-left:5%"><tr>
                <th>Computer </th>
                <th >Operating System</th>
                <th>Processor</th>
                 <th>Ver</th>
                  <th>RAM</th>
                <th>Serial #</th>
                <th>Reported</th>
               
               <th>iDrive</th>
                <th>App</th>
             </tr>
             @foreach($devices as $device)
             <tr>
                    <td>{{$device->ComputerName}}</td>
                    <td><?php
                    $osArray = json_decode($device->OperatingSystem, true);
                    $os = '';
                    foreach ($osArray as $item) {
                      if (isset($item['Key']) && $item['Key'] === 'OS') {
                        $os = $item['Value'];
                        break;
                      }
                    }
                    echo str_replace('Microsoft', '', $os);
                    ?></td>
                    <td>{{$device->Processor ?? '-'}}</td>
                    <?php
                    $serial = '-';
                    foreach ($osArray as $item) {
                      if (isset($item['Key']) && $item['Key'] === 'Version') {
                        $serial = $item['Value'];
                        break;
                      }
                    }
                    ?>
                    <td @if($siteconstants->updated_version!=$serial) style="color:red" @endif >
                  {{$serial}}  
                  </td>
                      <td>
                        <?php
                        $ram = json_decode($device->MemoryModules, true);
                        echo is_array($ram) ? $ram[0] : $ram;
                        ?>
                      </td>
                    <td><?php
                    $serial = '';
                    foreach ($osArray as $item) {
                      if (isset($item['Key']) && $item['Key'] === 'Serial Number') {
                        $serial = $item['Value'];
                        break;
                      }
                    }
                    echo $serial;
                    ?></td>
                    <td>{{date('Y-m-d',strtotime($device->updated_at))}}</td>
                    <td @if($siteconstants->idrive!=$device->onedrive) style="color:red" @endif>
                        {{ $device->onedrive }}
                    </td>
                   
                    <td @if($siteconstants->app_version!=$device->AppVersion) style="color:red" @endif>{{$device->AppVersion}}</td>
                 </tr>
                 @endforeach
                 
                   </table><div id="pageFooter">
    
</div>
<script type="text/javascript">window.print(); setTimeout(function() { window.close(); }, 100);</script>
</body></html>