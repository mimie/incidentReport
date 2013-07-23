<?php

/*
 *@return ticket details in array
 */
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

/*
 *typeId is type_id from ticket details
 *@return incident Type
 */
function getIncidentType($typeId){

  $typeId = mysql_real_escape_string($typeId);
  $sql = "SELECT name FROM ticket_type WHERE id = {$typeId}";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);

  $incidentName = $row["name"];

  if($incidentName == 'S1 - Network Outage'){
    return "Regular Outage";
  }

  else{
    return "Network Outage";
  }
}

/*
 *customerUserId is the customer_user_id from ticket details
 *@return site Affected
 */
function getSiteAffected($customerUserId){
  
  $customerUserId = mysql_real_escape_string($customerUserId);
  $sql = "SELECT location FROM customer_user WHERE login='{$customerUserId}'";
  $result = mysql_query($sql) or die (mysql_error());
  $row = mysql_fetch_assoc($result);

  $siteAffected = $row["location"];

  return $siteAffected;
}

/*
 *customerUserId is the customer_user_id from ticket details
 *@return company name
 */
function getCompanyName($customerUserId){

  $customerUserId = mysql_real_escape_string($customerUserId);
  $sql = "SELECT COMPNME FROM customer_user WHERE login='{$customerUserId}'";
  $result = mysql_query($sql) or die (mysql_error());
  $row = mysql_fetch_assoc($result);

  $companyName = $row["COMPNME"];
  $companyName = preg_replace("/\([^)]+\)/","",$companyName);
  
  return $companyName;
}

/*
 *ticket Id
 *@return circuit id of a specific ticket number
 */
function getCircuitId($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT cv.name FROM configitem_version cv, link_relation lr, configitem ci\n"
       . "WHERE (cv.id = ci.last_version_id AND ci.id = lr.source_key)\n"
       . "AND lr.target_key  = '{$ticketId}' ORDER BY cv.create_time DESC";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $circuitId = $row["name"];

  return $circuitId;

}

function getReporter($ticketId){
  
  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT value_text FROM dynamic_field_value WHERE object_id = '{$ticketId}' AND field_id = 50";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $reporter = $row["value_text"];

  return $reporter;
}
?>
