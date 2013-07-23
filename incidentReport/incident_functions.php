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

?>
