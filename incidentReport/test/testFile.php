<?php
 
  include '../dbcon.php';
  include '../incident_functions.php';

  $details = getTicketDetails("229");
  var_dump($details);

?>
