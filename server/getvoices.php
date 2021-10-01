<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();


$sql = "SELECT * FROM media_uploads WHERE `memorial_un_id` = '".$_POST['deceased_code']."' AND `media_type`= 'voice' ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($voice_uploads = $stmt->fetch(PDO::FETCH_ASSOC)){

	echo '<div style="border-radius: 2px;" class="bg-white row m-1 p-3 mb-3">
		     <audio controls="controls">
				Your browser does not support the audio element.
				<source src="https://funeral.myeventsgh.com/memorial_recording/'.$voice_uploads['media_url'].'" type="audio/wav">
				<source src="https://funeral.myeventsgh.com/memorial_recording/'.$voice_uploads['media_url'].'" type="audio/wav">
			 </audio>
			 <div class="col-md-9 mt-3">
				<p style="color: #F6AE00;">Voice by '.$voice_uploads['uploaded_by'].' on '.$voice_uploads['upload_time'].'</p>
			 </div>
		  </div>';
}
?>