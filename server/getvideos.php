<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();


$sql = "SELECT * FROM media_uploads WHERE `deceased_name` = '".$_POST['deceased_name']."' AND `media_type`= 'video' ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($video_uploads = $stmt->fetch(PDO::FETCH_ASSOC)){

  echo '<div style="border-radius: 2px;" class="bg-white row m-1 p-3 mb-3 justify-content-center align-items-center">
         <video class="col-md-12" controls="controls">
        Your browser does not support the video tag.
        <source src="https://funeral.myeventsgh.com/'.$video_uploads['media_url'].'" type="video/mp4; codecs="hevc" ">
        <source src="https://funeral.myeventsgh.com/'.$video_uploads['media_url'].'" type="video/mp4; codecs="av01.0.00M.08, opus" ">
        <source src="https://funeral.myeventsgh.com/'.$video_uploads['media_url'].'" type="video/mp4; codecs="avc1.4D401E, mp4a.40.2" ">
        <source src="https://funeral.myeventsgh.com/'.$video_uploads['media_url'].'" type="video/ogg; codecs="theora, vorbis" ">
       </video>
       <div class="col-md-9 mt-3">
        <p style="color: #F6AE00;">Video by '.$video_uploads['uploaded_by'].' on '.$video_uploads['upload_time'].'</p>
       </div>
      </div>';
}
?>