<?php
 
  include '../dbcon.php';
  include '../incident_functions.php';

  $details = getTicketDetails("229");
  var_dump($details);

  $type_id = $details["type_id"];
  $incidentType = getIncidentType($type_id);
  var_dump($incidentType);
 

?>
