<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT `memorial_id`, `deceased_name`, `year_of_birth`, `quote`, `life_story`, `memorial_un_id` FROM memorial WHERE `memorial_un_id` = '".$_POST['deceased_code']."' ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($memorial = $stmt->fetch(PDO::FETCH_ASSOC)){

$life_array = array('memorial_id'=> $memorial['memorial_id'], 'memorial_deceased_name'=> $memorial['deceased_name'], 'yob_yod'=> $memorial['year_of_birth'],  'life_story'=>$memorial['life_story'],
	'memorial_un_id'=>$memorial['memorial_un_id']);
echo json_encode($life_array); /*'<div style="border-radius: 2px;" class="bg-white row m-1 p-3 mb-3">
							<div class="col-md-2">
								<img src="assets/img/ic_candle2.png">
							</div>

							<div class="col-md-9">
								<h3 style="color: #F6AE00;">'.$memorial['deceased_name'].' '.$memorial['year_of_birth'].'</h3>
								<p>'.$memorial['life_story'].'</p>
							
							</div>
                         
						</div>'*/;

}					
?>