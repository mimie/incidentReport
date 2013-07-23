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

/*
 *@return name,email,source who reported the incident
 */
function getReporter($ticketId){
  
  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT value_text FROM dynamic_field_value WHERE object_id = '{$ticketId}' AND field_id = 50";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $reporter = $row["value_text"];

  return $reporter;
}

function getFaultClosedTime($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT create_time FROM ticket_history\n" 
       . "WHERE history_type_id ='27'\n"
       . "AND ticket_id = '{$ticketId}'"
       . "AND name LIKE '%\%\%closed successful\%\%'";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);

  $create_time = $row["create_time"];
  
  if(isset($create_time)){
    //$create_time = date("M j, Y / h:i A",strtotime($create_time));
    return $create_time;
  }
}

function getOutageTime($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT value_date FROM dynamic_field_value WHERE object_id = '{$ticketId}' AND field_id = 36";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $outageTime = $row["value_date"];

  if($outageTime){
     //$outageTime = date("M j, Y / h:i A",strtotime($outageTime));
     return $outageTime;
  }

  else{
     $ticketDetails = getTicketDetails($ticketId);
     $outageTime = $ticketDetails["create_time"];
     //$outageTime = date("M j, Y / h:i A",strtotime($outageTime));
     return $outageTime;
  }
}

function getResolvedOutageTime($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT value_date FROM dynamic_field_value\n"
       . "WHERE object_id = '{$ticketId}'\n"
       . "AND field_id ='37'";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $outageResolvedTime = $row["value_date"];

  if($outageResolvedTime){
    //$outageResolvedTime = date("M j, Y / h:i A",strtotime($outageResolvedTime));
    return $outageResolvedTime;
  }

  else{
    $outageResolvedTime = getFaultClosedTime($ticketId);
    return $outageResolvedTime;
  }
  
}

function timeFormat($time){

   $timeFormat = date("M j, Y / h:i A",strtotime($time));
   return $timeFormat;
}

function getOutageDuration($outageTime,$resolvedTime){
   
  $totalOutageSec = strtotime($resolvedTime)-strtotime($outageTime);
 
  if($totalOutageSec >= 60){
    $total_outage_hrs = 0;
    $total_outage_mins = $totalOutageSec / 60;
        if ($total_outage_mins >= 60){
              $total_outage_hrs = floor($total_outage_mins / 60);
              $total_outage_mins = ceil($total_outage_mins - ($total_outage_hrs*60));
                } 
        else{
              $total_outage_mins = ceil($total_outage_mins);
            }
   }else{
       $total_outage_hrs = 0;
       $total_outage_mins = 0;
   }

 return $total_outage_hrs." hr(s). ".$total_outage_mins." min(s).";

}
