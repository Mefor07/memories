<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();
$sql = "SELECT  `memorial`.created_by, `creators`.email_address, `creators`.plan FROM memorial, creators WHERE `memorial`.deceased_name = '".$_POST['deceased_name']."'
AND `memorial`.created_by = `creators`.email_address";
$stmt = $dbConn->prepare($sql);
$stmt->execute();

while($plan = $stmt->fetch(PDO::FETCH_ASSOC)){

	echo $plan['plan'];
}


?>