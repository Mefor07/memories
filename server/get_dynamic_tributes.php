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

$tribute['tribute_message']	 = $out = strlen($tribute['tribute_message']) > 50 ? substr($tribute['tribute_message'],0,150)."..." : $tribute['tribute_message'];

$code = $tribute['memorial_un_id'];
	//$tribute['tribute_message'] = 'Kenneth Quartey saved my life. He and four others. It was a warm balmy Saturday afternoon and we, close friends of the Quarteys, had collected, as was our wont 30 odd years ago, at the Scott family chalet in Ada. Bobbing in the waters, moored off the quay, sat Kenneth’s pride and joy, a one man sailing catamaran that was no more than a dinghy. It was on this, in hindsight, rather precarious vessel that we both decided to sail downstream to gate-crash another party';
echo '<div class="row  align-items-center testimonial">
                                              	    <p class="col-md-12 p-3"><img style="width:30px;" src="assets/p2_assets/ic_flower.png"></p>
	                                              	<p class="col-md-12 mt-3 p-3 ">'.$tribute['tribute_message'].'</p>
	                                              	<p class="col-md-12 mt-1 p-3 "> Tribute By - '.$tribute['tribute_by'].'</p>
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