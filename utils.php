<?php

function logActivity($db, $id, $action) {
  $ipAddress = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) 
      ? $_SERVER['HTTP_X_FORWARDED_FOR'] 
      : $_SERVER['REMOTE_ADDR'];
  $userAgent = $_SERVER['HTTP_USER_AGENT'];

  $stmt = $db->prepare('INSERT INTO activity_log (user_id, action, ip_address, user_agent) VALUES (:user_id, :action, :ip_address, :user_agent)');
  $stmt->execute([
      ':user_id' => $id,
      ':action' => $action,
      ':ip_address' => $ipAddress,
      ':user_agent' => $userAgent
  ]);
}


?>