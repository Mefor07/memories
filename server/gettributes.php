<?php
header('Content-Type: text/plain');
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT * FROM tributes WHERE `memorial_un_id` = '".$_POST['deceased_code']."' ORDER BY `tribute_id` DESC";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
//str_replace('search', '%20', $_POST['deceased_name']);
while($tribute = $stmt->fetch(PDO::FETCH_ASSOC)){

	$code = $tribute['memorial_un_id'];
	//$tribute['tribute_message'] = 'Kenneth Quartey saved my life. He and four others. It was a warm balmy Saturday afternoon and we, close friends of the Quarteys, had collected, as was our wont 30 odd years ago, at the Scott family chalet in Ada. Bobbing in the waters, moored off the quay, sat Kenneth’s pride and joy, a one man sailing catamaran that was no more than a dinghy. It was on this, in hindsight, rather precarious vessel that we both decided to sail downstream to gate-crash another party';
echo '<div class="col-md-12 mt-3 p-3 row  rounded-box">
		    			    	<div style="border-radius: 2px;" class="row col-md-12 m-1 p-1 mb-3">
									<div class="col-md-2 text-center">
										<img style="width:30px;" src="assets/p2_assets/ic_flower.png">
									</div>

									<div class="row col-md-10">
										<div class="row col-md-6 mb-0">
											<div class=" text-center">
												<p id="peoples_tribute_by">Posted by '. $tribute['tribute_by'].'</p>
											</div>

											
										</div>

										<div class="col-md-6 ml-auto">
											<div class="row">
												<div class="col-3 col-md-3 col-sm-1 my-auto m-1 ml-auto"><span style="font-size:15px; color: #565656;">Share to</span></div>
												<div class="col-2 col-md-2 col-sm-2   my-auto m-1"><a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode("https://funeral.myeventsgh.com/profile.php?k=$code#tributes").'" target="_blank"><img style="width:10px;" src="assets/p2_assets/ic_facebook.png"></a></div>
												<div class="col-2 col-md-2 col-sm-2   my-auto m-1"><a href="https://api.whatsapp.com/send?text='.urlencode("https://funeral.myeventsgh.com/profile.php?k=$code#tributes").'"><img style="width:20px;" src="assets/p2_assets/ic_whatsapp.png"></a></div>
												<div class="col-2 col-md-2 col-sm-2   my-auto m-1"><a href="#"><img style="width:20px;" src="assets/p2_assets/ic_mail.png"></a></div>
											</div>
										</div>

										<p id="peoples_tribute" style="color: #565656; background-color: transparent; " class="col-md-12 mt-5">'.$tribute['tribute_message'].'</p>
									</div>

									
		                         
								</div>
		    			    </div>';                   
		    			/*'<div style="border-radius: 2px;" class="bg-white row m-1 p-3 mb-3">
							<div class="col-md-2">
								<img src="assets/img/ic_candle2.png">
							</div>

							<div class="col-md-9">
								<p style="color: #F6AE00;">Posted by '.$tribute['tribute_by'].' on '.$tribute['tribute_date'].'</p>
								<p>'.$tribute['tribute_message'].'</p>
							
							</div>
                         
						</div>'*/;
}
?>