<?php

  /*ob_start();
  require_once("dompdf/dompdf_config.inc.php");*/
?>
<html>
<head>
<!--<link rel="stylesheet" type="text/css" url="design.css">-->
<style type="text/css">
div{
  padding:5px 10px 5px 10px;
}
#logo{
 height:60px;
 width:150px;
}

#title{
  background-color:#FBB117;
  text-align:center;
  color:black;
  font-weight:bold;
  font-family:Courier New,serif;
  font-size: 110%;
  letter-spacing:22px;
}

#info{
  background-color:#C0C0C0;
  color:black;
  font-weight:bold;
  font-family:Courier New;
  font-size: 110%;
  letter-spacing:4px;
}

table{
  padding:4px;
  font-family:Courier New,serif;
  font-size:large;
}

#details{
  font-family:Courier New,serif;
  font-size:large;
}
</style> 
<title>Incident Report</title>
</head>
<body>
<script type="text/php">
         if (isset($pdf)){
           $font = Font_Metrics::get_font("helvetica", "bold");
           $pdf->page_text(420, 565, "{PAGE_NUM}", $font, 9, array(0,0,0));
         }
</script>


<?php

  include 'dbcon.php';
  include 'incident_functions.php';

  //$ticketNumber = $_GET['ticketNumber'];
  $ticketNumber = '1093994'; 
  $ticketId = getTicketId($ticketNumber);
  $ticketDetails = getTicketDetails($ticketId);
  $customerUserId = $ticketDetails["customer_user_id"];
  $companyName = getCompanyName($customerUserId);
 
  $ticketNumber = $ticketDetails["ticketNumber"];

  $typeId = $ticketDetails["type_id"];
  $incidentType = getIncidentType($typeId);
  $siteAffected = getSiteAffected($customerUserId);
  $circuitId = getCircuitId($ticketId);
  $reportedBy = getReporter($ticketId);
  $faultCreateTime = $ticketDetails["create_time"]; 
  $faultClosedTime = getFaultClosedTime($ticketId);
  $outageTime = getOutageTime($ticketId);
  $resolvedTime = getResolvedOutageTime($ticketId);
  $outageDuration = getOutageDuration($outageTime,$resolvedTime);
  $reasonForOutage = getReasonForOutage($ticketId);  

  $restorationDetails = displayRestorationDetails($ticketId);

  $openCreateId = $ticketDetails["create_by"];
  $closeCreateId = getAgentCloseId($ticketId);
  $faultHandlers = getFaultHandlerNames($openCreateId,$closeCreateId);
  $troubleReported = $ticketDetails["title"];
  $containmentAction = getContainmentAction($ticketId);
  $correctiveAction = getCorrectiveAction($ticketId);
  
?>

<div>
 <img id="logo" src="globe_logo.png">
</div>

<div id="title">
 INCIDENT REPORT
</div>

<div id="details">
Dear <b><?=$companyName?></b>,<br>
Please accept our apology for the downtime encountered on your subscribed service/s.
<br><br>
</div>

<div id="info">
CUSTOMER INFORMATION
</div>
<table>
  <tr>
   <td width="40%"><b>Ticket Number</b></td>
   <td width="60%">: <?=$ticketNumber?></td>
  </tr>
  <tr>
   <td width="40%"><b>Incident Type</b></td>
   <td width="60%">: <?=$incidentType?></td>
  </tr>
  <tr>
   <td width="40%"><b>Site Affected</b></td>
   <td width="60%">: <?=$siteAffected?></td>
  </tr>
  <tr>
   <td width="40%"><b>Circuit ID</b></td>
   <td width="60%">: <?=$circuitId?></td>
  </tr>
  <tr>
   <td width="40%"><b>Reported by</b></td>
   <td width="60%">: <?=$reportedBy?></td>
  </tr>
  <tr>
   <td width="40%"><b>Fault Reported Date/Time</b></td>
   <td width="60%">: <?=timeFormat($faultCreateTime)?></td>
  </tr>
  <tr>
   <td width="40%"><b>Fault Closed Date/Time</b></td>
   <td width="60%">: <?=timeFormat($faultClosedTime)?></td>
  </tr>
  <tr>
   <td width="40%"><b>Outage Date/Time</b></td>
   <td width="60%">: <?=timeFormat($outageTime)?></td>
  </tr>
  <tr>
   <td width="40%"><b>Outage Resolved Date/Time</b></td>
   <td width="60%">: <?=timeFormat($resolvedTime)?></td>
  </tr>
  <tr>
   <td width="40%"><b>Outage Duration</b></td>
   <td width="60%">: <?=$outageDuration?></td>
  </tr>
<table>

<div id="info">
FAULT HANDLED BY
</div>
<div id="details"><?=$faultHandlers?></div>

<div id="info">
DEFINITION OF THE PROBLEM
</div>
<div id="details"><?=$troubleReported?></div>

<div id="info">
CONTAINMENT/INTERIM ACTIONS
</div>
<div id="details"><?=$containmentAction?></div>

<div id="info">
REASON FOR OUTAGE
</div>
<div id="details"><?=$reasonForOutage?></div>

<div id="info">
CORRECTIVE ACTIONS
</div>
<div id="details"><?=$correctiveAction?></div>

<div id="info">
PREVENTIVE ACTIONS
</div>
<div id="details">Here is the preventive actions.</div>

<div id="info">
RESTORATION DETAILS
</div>
<div>
<?php echo $restorationDetails;?>
</div>
</body>
</head>
</html>
<?php
  /*$html = ob_get_clean();
  $pdfFile = generatePDF($html,$ticketNumber);*/
?>
