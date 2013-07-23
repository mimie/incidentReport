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
  font-family:Courier New;
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
}
</style> 
<title>Incident Report</title>
</head>
<body>

<?php

  include 'dbcon.php';
  include 'incident_functions.php';

  $ticketId = $_GET["ticketId"];
  $ticketDetails = getTicketDetails($ticketId);
  $customerUserId = $ticketDetails["customer_user_id"];
  $companyName = getCompanyName($customerUserId);
 
  $ticketNumber = $ticketDetails["ticketNumber"];

  $typeId = $ticketDetails["type_id"];
  $incidentType = getIncidentType($typeId);
  $siteAffected = getSiteAffected($customerUserId);
  $circuitId = getCircuitId($ticketId);

?>

<div>
 <img id="logo" src="globe_logo.png">
</div>

<div id="title">
 INCIDENT REPORT
</div>

<div id="message">
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
   <td width="60%">: mgbgestoso@bpi.com.ph</td>
  </tr>
  <tr>
   <td width="40%"><b>Fault Reported Date/Time</b></td>
   <td width="60%">: Nov 11, 2012 / 02:33 AM</td>
  </tr>
  <tr>
   <td width="40%"><b>Fault Closed Date/Time</b></td>
   <td width="60%">: Nov 11, 2012 / 11:46 AM</td>
  </tr>
  <tr>
   <td width="40%"><b>Outage Date/Time</b></td>
   <td width="60%">: Nov 11, 2012 / 02:33 AM</td>
  </tr>
  <tr>
   <td width="40%"><b>Outage Resolved Date/Time</b></td>
   <td width="60%">: Nov 11, 2012 / 02:33 AM</td>
  </tr>
  <tr>
   <td width="40%"><b>Outage Duration</b></td>
   <td width="60%">: Nov 11, 2012 / 02:33 AM</td>
  </tr>
<table>

<div id="info">
FAULT HANDLED
</div>
<div>Describe the fault handled.</div>

<div id="info">
DEFINITION OF THE PROBLEM
</div>
<div>Here is the defintion of the problem.</div>

<div id="info">
CONTAINMENT/INTERIM ACTIONS
</div>
<div>Here is the interim actions.</div>

<div id="info">
REASON FOR OUTAGE
</div>
<div>Here is the reason for outage.</div>

<div id="info">
CORRECTIVE ACTIONS
</div>
<div>Here is the corrective actions.</div>

<div id="info">
PREVENTIVE ACTIONS
</div>
<div>Here is the preventive actions.</div>

<div id="info">
RESTORATION DETAILS
</div>
<div>Restoration Details.</div>

</body>
</head>
</html>
