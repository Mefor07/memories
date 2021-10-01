<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT * FROM media_uploads WHERE `memorial_un_id` = '".$_POST['deceased_code']."' AND `media_type`= 'image' ORDER BY `upload_id` DESC ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
$imgs_array = array();
while($media_uploads = $stmt->fetch(PDO::FETCH_ASSOC)){
   array_push($imgs_array, $media_uploads['media_url']);
}

echo json_encode($imgs_array);
?>