<?php
header('Content-Type: text/plain');
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();
$sql = "SELECT * FROM donations_completed WHERE `donation_for` = '".$_POST['deceased_name']."' AND `response_code` = '0000' ORDER BY `donation_comp_id` DESC";
$stmt = $dbConn->prepare($sql);
$stmt->execute();

while($donation = $stmt->fetch(PDO::FETCH_ASSOC)){
	//$tribute['tribute_message'] = 'Kenneth Quartey saved my life. He and four others. It was a warm balmy Saturday afternoon and we, close friends of the Quarteys, had collected, as was our wont 30 odd years ago, at the Scott family chalet in Ada. Bobbing in the waters, moored off the quay, sat Kenneth’s pride and joy, a one man sailing catamaran that was no more than a dinghy. It was on this, in hindsight, rather precarious vessel that we both decided to sail downstream to gate-crash another party';
echo '<div class="col-md-12 mt-3 p-3 tribute-form row ">
		    			    	<div style="border-radius: 2px;" class="row col-md-12 m-1 p-1 mb-3">
									<div class="col-md-2 text-center">
										<img style="width:30px;" src="assets/p2_assets/ic_money.png">
									</div>

									<div class="row col-md-10">
										<div class="row col-md-12 mb-0">
											<div class=" text-center">
												<p style="color: #565656; font-size: 34px;"> '. $donation['amount'].' Cedis Donation</p>
											</div>

											
										</div>

										

										<p style="color: #565656; background-color: transparent; " class="col-md-12">'.$donation['amount'].' Cedis funus donation by '.$donation['donator_name'].'</p>
									</div>

									
		                         
								</div>
		    			    </div>';                   
		    			
}
?>