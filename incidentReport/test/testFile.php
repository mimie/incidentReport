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

  $circuitId = getCircuitId("229");
  var_dump($circuitId);

  $reporter = getReporter("229");
  var_dump($reporter);

  $faultClosedTime = getFaultClosedTime("135804");
  var_dump($faultClosedTime);

  $outageTime = getOutageTime("135804");
  var_dump($outageTime);

  $outageResolvedTime = getResolvedOutageTime("135804");
  var_dump($outageResolvedTime);

  $outageDuration = getOutageDuration($outageTime,$outageResolvedTime);
  var_dump($outageDuration);

  $rfo = getReasonForOutage("299");
  var_dump($rfo);

  $restorationDetails = getRestorationDetails("299");
  var_dump($restorationDetails);

  $htmlRestoration = displayRestorationDetails("299");
  var_dump($htmlRestoration);

  $agentCloseId = getAgentCloseId("299");
  var_dump($agentCloseId);

  $agentOpenId = $details["create_by"];
  $faultHandlers = getFaultHandlerNames($agentCloseId,$agentOpenId);
  var_dump($faultHandlers);

  $containmentActions = getContainmentAction("299");
  var_dump($containmentActions);

  $ticketId = getTicketId('1093994');
  var_dump($ticketId);

  
?>
