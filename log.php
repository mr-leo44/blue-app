<?php 
$v = $utilisateur->GetSettingValue('7');
if($v == '1'){ 
		// Get current page URL 
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; 
		$currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; 
		 
		// Get server related info 
		$user_ip_address = $_SERVER['REMOTE_ADDR']; 
		$referrer_url = !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/'; 
		$user_agent = $_SERVER['HTTP_USER_AGENT']; 
		 
		 
		 $id=Utils:: uniqUid('visitor_logs', 'id', $db);
		// Insert visitor log into database 
		$sql = "INSERT INTO visitor_logs (page_url, referrer_url, user_ip_address, user_agent, created, user_id, request_content) VALUES (:page_url, :referrer_url, :user_ip_address, :user_agent,NOW(), :user_id, :request_content)"; 
		$stmt = $db->prepare($sql);  
		   // $stmt->bindParam(":id", $id);
		   $stmt->bindParam(":page_url", $currentURL);
		   $stmt->bindParam(":referrer_url", $referrer_url);
		   $stmt->bindParam(":user_ip_address", $user_ip_address);
		   $stmt->bindParam(":user_agent", $user_agent); 
		   $stmt->bindValue(":request_content", json_encode($_REQUEST)); 
		   $stmt->bindParam(":user_id", $utilisateur->code_utilisateur); 
		   
		$insert = $stmt->execute(); 
}
?>