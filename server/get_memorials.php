<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();


$sql = "SELECT * FROM memorial WHERE `created_by` = '".$_POST['email']."' ";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($memorials = $stmt->fetch(PDO::FETCH_ASSOC)){
	            
	echo '<div class="col-md-4 mb-5">
                  <div class="image-container mb-3">
                

                    <a href="https://funeral.myeventsgh.com/profile.php?k='.$memorials['memorial_un_id'].'"><img src="./funus/'.$memorials['image_url'].'" class="img-responsive fit-image"/></a>

                    <div class="property-label">'.$memorials['year_of_birth'].'</div>
                  </div>
                  <h5 style="color: #777;" class="ml-auto"><small>'.str_replace("_", " ", $memorials['deceased_name']).'</small></h5>
                  <h6 style="color: #777;" class="ml-auto"><small>'.$memorials['quote'].'</small></h6>
                  <div class="row">
                    <div class="col-lg-4 col-md-3 col-sm-3 m-1">
                      <button id="copy_link_btn" style="color: #fff !important" url="https://funeral.myeventsgh.com/profile.php?k='.$memorials['memorial_un_id'].' " class="" onClick="copy(this)">Copy link</button>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-3 m-1">
                      <button id="edit_memorial_btn" style="color: #fff !important" class="" name ="'.$memorials['memorial_un_id'].'" onClick="editMemorial(this.name)">Edit Memorial</button>
                    </div>
                  </div>
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