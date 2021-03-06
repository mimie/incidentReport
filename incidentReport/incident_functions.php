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

function getTicketId($ticketNumber){
  
  $ticketNumber = mysql_real_escape_string($ticketNumber);
  $sqlTicketNumber = "SELECT id FROM ticket where tn='{$ticketNumber}'";
  $result = mysql_query($sqlTicketNumber) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $ticketId = $row["id"];

  return $ticketId;
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

function timeFormatRestoration($time){

   $timeFormat = date("m/d/y h:i A",strtotime($time));
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

function getReasonForOutage($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $rfoSql = "SELECT value_text FROM dynamic_field_value\n" 
          . "WHERE object_id = '{$ticketId}' AND field_id = 57";
  $result = mysql_query($rfoSql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $reasonForOutage = $row["value_text"];

  isset($reasonForOutage) ? $reasonForOutage : $reasonForOutage = "No RFO indicated";

  return $reasonForOutage;
  
}

function getRestorationDetails($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $restorationDetails = array();
  $sql = "SELECT a_body,create_time FROM article\n" 
       . "WHERE article_type_id = 10\n" 
       . "AND article_sender_type_id = 1\n" 
       . "AND ticket_id = '{$ticketId}'";
  $result = mysql_query($sql) or die(mysql_error());

  while($row = mysql_fetch_assoc($result)){
    $time = $row["create_time"];
    $details = $row["a_body"];
    $restorationDetails[$time] = $details;
  }

  return $restorationDetails;
}

function displayRestorationDetails($ticketId){

  $restorationDetails = getRestorationDetails($ticketId);

  $html = "<table cellpadding='6' style='border:1px dotted #848484; border-collapse:collapse;'>";

  foreach($restorationDetails as $time=>$details){
    $html = $html."<tr>"
          . "<td align='center' width='25%' style='border:1px dotted #848484;'>".timeFormatRestoration($time)."</time>"
          . "<td width='75%' style='border:1px dotted #848484;'>$details</td>"
          . "</tr>";
  }

  $html = $html."</table>";
  return $html;
        
}


function getAgentCloseId($ticketId){

  $ticketId = mysql_real_escape_string($ticketId);
  $sql = "SELECT create_by FROM ticket_history\n" 
       . "WHERE history_type_id ='27'\n"
       . "AND ticket_id = '{$ticketId}'"
       . "AND name LIKE '%\%\%closed successful\%\%'";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);

  $closeId = $row["create_by"];
  return $closeId;

}

function getFaultHandlerNames($openCreateId,$closeCreateId){
 
  $openAgentName = getAgentName($openCreateId);
  $closeAgentName = getAgentName($closeCreateId);
  $faultHandlers = $openAgentName." / ".$closeAgentName;

  return $faultHandlers;
}

function getAgentName($agentId){

  $agentSql = "SELECT first_name,last_name FROM users where id='$agentId'";
  $result = mysql_query($agentSql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);

  $agentName = $row["first_name"]." ".$row["last_name"];
  return $agentName;
}

function getContainmentAction($ticketId){

  $dynamicField = "SELECT id FROM dynamic_field WHERE name='containtmentInterim'";
  $resultId = mysql_query($dynamicField) or die(mysql_error());
  $row = mysql_fetch_assoc($resultId);
  $dynamicFieldId = $row["id"];

  $sql = "SELECT value_text FROM dynamic_field_value\n"
       . "WHERE object_id = '$ticketId'\n"
       . "AND field_id = '$dynamicFieldId'";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $containmentAction = $row["value_text"];

  return $containmentAction;
}

function getCorrectiveAction($ticketId){

  $sql = "SELECT a_body FROM article WHERE ticket_id = '$ticketId' AND article_type_id = '10' ORDER BY create_time DESC LIMIT 1";
  $result = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_assoc($result);
  $correctiveAction = $row["a_body"];

  return $correctiveAction;
}

function generatePDF($html,$ticketNumber){

  require_once("dompdf/dompdf_config.inc.php");                                                 

  $fileName = "ireport_".$ticketNumber.".pdf";                                                                      
  $fileLocation = "pdf/".$fileName;                                        
     
  $dompdf = new DOMPDF();
  $dompdf->load_html($html);                                                                    
  $dompdf->set_paper('Letter','portrait');                                                         
     
  $dompdf->render();                                                                            
  file_put_contents($fileLocation, $dompdf->output( array("compress" => 0) ));                  
}

function generateRestorationPDF($html,$ticketNumber){

  require_once("dompdf/dompdf_config.inc.php");                                                 

  $fileName = "restorationDetails_".$ticketNumber.".pdf";                                                                      
  $fileLocation = "pdf/".$fileName;                                        
     
  $dompdf = new DOMPDF();
  $dompdf->load_html($html);                                                                    
  $dompdf->set_paper('Letter','portrait');                                                         
     
  $dompdf->render();                                                                            
  file_put_contents($fileLocation, $dompdf->output( array("compress" => 0) ));                  
}
?>
