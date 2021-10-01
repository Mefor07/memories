<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT `plan` FROM creators WHERE `email_address`= '".$_POST['creator_name']."' ";

$stmt = $dbConn->prepare($sql);
$stmt->execute();

while($plan = $stmt->fetch(PDO::FETCH_ASSOC)){

	echo $plan['plan'];
}

?>