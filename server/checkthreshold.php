<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT  `deceased_name`, `media_type` FROM media_uploads WHERE `deceased_name` = '".$_POST['deceased_name']."'
AND `media_type` = '".$_POST['media_type']."' ";

$stmt = $dbConn->prepare($sql);

$stmt->execute();
$count = $stmt->rowCount();


if($count >= $_POST['threshold']){
 echo "error";
}else{
 echo "success";
}

?>