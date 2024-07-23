<?php
$is_mobile  = isset($_GET['mobile'])?"?mobile=".$_GET['mobile']:''; 
header("Location: https://echodata.org/blue-test/login.php".$is_mobile);

?>