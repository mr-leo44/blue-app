<?php 

$reponse['new_version']= false; 
$reponse['url']=""; 
$reponse['forceUpdate']=false; 
$reponse['versionCode']=0; 
$reponse['versionName']="0"; 
$reponse['updateMessage']=""; 
$reponse['appID']="0"; 
/*
{
  "url":"http://127.0.0.1:8080/blue-app-dev/apk/app-debug.apk",
  "versionCode":4,
  "versionName":"4.0.5",
  "updateMessage":"Il y a une nouvelle version de l\'application"
}*/
$string = file_get_contents("version.json");
// $string = file_get_contents("update.json");
//var_dump($string);
//if (!empty($string)) {
    // deal with error...

		$json = json_decode($string, true);
		//echo '<pre>'; print_r($json); exit;
		//if ($json != null) { 


// var_dump($json);		
			//Serveur
			$reponse['versionName']=isset($json['versionName'])?$json['versionName']:"0";
			
			//Client
			$existingVersion=isset($_GET['versionName'])?$_GET['versionName']:"0.0";
			$existingVersionCode=isset($_GET['versionCode'])?$_GET['versionCode']:"0";
			$existingAppID=isset($_GET['appID'])?$_GET['appID']:"0";
			
			$newVersion = $reponse['versionName']; 
			$newValue = 0;
			$oldValue = 0;

// var_dump($_GET);
// var_dump($reponse);
/*
			if (!isset($existingVersion) || !isset($newVersion)) {
			 return false;
			}*/
			
			$newVersionIsGreater = false;
			$existingVersionArray = explode(".", $existingVersion);
			// var_dump($existingVersionArray);
			$newVersionArray = explode(".", $newVersion);
			if(isset($existingVersionArray[0]) && isset($newVersionArray[0])){ 
				$oldValue= (int) $existingVersionArray[0];
				$newValue= (int) $newVersionArray[0];
				//Major Version
				if($oldValue < $newValue){
					 $newVersionIsGreater = true;
				}else if($oldValue == $newValue){
					//Minor Version
						$newValue= (int) (isset($newVersionArray[1])?$newVersionArray[1]:0);
						$oldValue= (int) (isset($existingVersionArray[1])?$existingVersionArray[1]:0);
						if($oldValue < $newValue){
							 $newVersionIsGreater = true;
						}else if($oldValue == $newValue){
							//Patch Version 
								$newValue= (int) (isset($newVersionArray[2])?$newVersionArray[2]:0);
								$oldValue= (int) (isset($existingVersionArray[2])?$existingVersionArray[2]:0);
								if($oldValue < $newValue){
									 $newVersionIsGreater = true;
								}
						}
				}
			}
			//var_dump($newVersionIsGreater);
			if($newVersionIsGreater == true){
				$reponse['new_version']= $newVersionIsGreater; 
				$reponse['url']=isset($json['url'])?$json['url']:""; 
				$reponse['forceUpdate']=isset($json['forceUpdate'])?$json['forceUpdate']:false; 
				$reponse['versionCode']=isset($json['versionCode'])?$json['versionCode']:"0"; 
				$reponse['versionName']=isset($json['versionName'])?$json['versionName']:"1"; 
				$reponse['updateMessage']=isset($json['updateMessage'])?$json['updateMessage']:""; 
				$reponse['appID']=isset($json['appID'])?$json['appID']:""; 
			}
		//}
//}
echo json_encode($reponse);
exit;