<?php
header('Content-type: application/json');

include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT * FROM memorial WHERE `memorial_un_id` = '".$_POST['deceased_code']."' ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();

while($memorial = $stmt->fetch(PDO::FETCH_ASSOC)){
    
    $memorial_id = $memorial['memorial_id'];
	$deceased_name = $memorial['deceased_name'];
	$year_of_birth = $memorial['year_of_birth'];
	$quote = $memorial['quote'];
	$life_story = $memorial['life_story'];
	$memorial_un_id = $memorial['memorial_un_id'];


	$data = array('deceased_name'=> $deceased_name,
              'year_of_birth' => $year_of_birth,
              'quote' => $quote,
              'life_story' => $life_story,
              'memorial_id'=> $memorial_id,
              'memorial_un_id' => $memorial_un_id);


}




echo json_encode($data);
?>