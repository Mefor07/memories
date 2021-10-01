<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();


$sql = "SELECT * FROM media_uploads WHERE `memorial_un_id` = '".$_POST['deceased_code']."' AND `media_type`= 'image' ORDER BY `upload_id` DESC ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($media_uploads = $stmt->fetch(PDO::FETCH_ASSOC)){
	            
	echo '<div class="col-md-4 mt-2 mb-1">
                  <div class="image-container mb-3">
                

                    <a href="#"><img onClick="showImage(this.src)" style="filter: drop-shadow(10px 8px 31px rgba(0, 0, 0, 0.25)); border-radius: 10px; width: 90%;" src="./funus/'.$media_uploads['media_url'].'" class="img-responsive fit-image"/></a>

                    <!--<div class="property-label">'.$media_uploads['upload_time'].'</div>-->
                  </div>
                  <!--<h5 style="color: #120f2d;" class="ml-auto"><small> Uploaded By '.$media_uploads['uploaded_by'].'</small></h5>-->
                  <!--<h6 style="color: #777;" class="ml-auto"><small>'.$media_uploads['deceased_name'].'</small></h6>-->
                  <!--<button url="http://178.79.172.242:8070/mefor/funus/profile.php?name='.$media_uploads['deceased_name'].' " class="btn" onClick="copy(this)">Copy link</button>-->
                  <!--<h6 class="lead ml-auto"><i class="lni-map-marker size-xs"></i><small> North Legon</small></h6>

                  <div class=" ml-auto cart-btn">
                      <div class="icon row">
                        <span class="text-center col-1"><i class="fas fa-plus"></i></span>
                        <span class="text-center col-1 my-auto"><i class="fas fa-minus"></i></span>
                        <span class="col-5 ">
                          <input style="padding: 1px !important; vertical-align: middle !important; height: 80%; outline: none; box-shadow: none;" type="number" class="form-control"/>
                        </span>
                      </div>    
                  </div>--> 
                </div>';
    
}
?>