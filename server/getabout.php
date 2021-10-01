<?php
include_once('../api/dbconnect.php');
$db = new DbConnect();
$dbConn = $db->connect();

$sql = "SELECT * FROM memorial WHERE `memorial_un_id` = '".$_POST['deceased_code']."' ";


$stmt = $dbConn->prepare($sql);
$stmt->execute();
while($quote = $stmt->fetch(PDO::FETCH_ASSOC)){


echo $quote['quote']; /*'<div class="col-md-12 justify-content-center align-items-center">

					<div class="col-md-12 text-center mb-5 mt-3">
						<q style="font-size: 35px; font-weight: 500; color:#120f2d;"><i>'.$quote['quote'].'</i></q>
				    </div>
				</div> 
				'*/;

}	

?>