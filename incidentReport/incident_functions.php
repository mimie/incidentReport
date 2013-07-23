<?php

function getTicketDetails($ticketId){

  $details = array();
  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT tn, title, type_id, customer_user_id, create_time, create_by\n"
       . "FROM ticket WHERE id = {$ticketId}";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);

  $details["ticketId"] = $ticketId;
  $details["ticketNumber"] = $row["tn"];
  $details["title"] = $row["title"];
  $details["type_id"] = $row["type_id"];
  $details["customer_user_id"] = $row["customer_user_id"];
  $details["create_time"] = $row["create_time"];
  $details["create_by"] = $row["create_by"];

  return $details;
  
}
?>
