<?php
$is_mobile  = isset($_GET['mobile'])?"?mobile=".$_GET['mobile']:'';
 header("Location: login.php".$is_mobile);
 // header("Location: https://echodata.org/blue-app-dev/login.php".$is_mobile);

?>