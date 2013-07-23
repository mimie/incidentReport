<?php
 
  include '../dbcon.php';
  include '../incident_functions.php';

  $details = getTicketDetails("229");
  var_dump($details);

  $type_id = $details["type_id"];
  $incidentType = getIncidentType($type_id);
  var_dump($incidentType);

  /*$string = "ABC (Test1)";
  echo preg_replace("/\([^)]+\)/","",$string); // 'ABC '
  */

  $customerUserId = $details["customer_user_id"];
  $siteAffected = getSiteAffected($customerUserId);
  var_dump($siteAffected);

  $companyName = getCompanyName($customerUserId);
  var_dump($companyName);
  
?>
