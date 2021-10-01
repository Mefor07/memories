<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();


$sql = "SELECT `image_url` FROM memorial WHERE `memorial_un_id` = '".$_POST['deceased_code']."' ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($image_url = $stmt->fetch(PDO::FETCH_ASSOC)){

	echo $image_url['image_url'];
}
?>