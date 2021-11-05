<?php

   class Api extends Rest{

        public $dbConn; 
   	
   	    public function __construct(){
            parent::__construct();
            $db = new DbConnect;
            $this->dbConn = $db->connect();
        }

        
        public function generateToken(){

        	$email_address = $this->validateParameters('email_address', $this->data['email_address'], STRING);

          $password = $this->validateParameters('password', md5($this->data['password']), STRING);
        	
          try{
            $stmt = $this->dbConn->prepare("SELECT * FROM creators WHERE email_address = :email_address AND password = :password");
             
            $stmt->bindParam(":email_address", $email_address);
            
            $stmt->bindParam(":password", $password);

            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!is_array($user)){

              $this->returnResponse(INVALID_USER_PASS, "email_address or Password is incorrect.");
            }

            /*
            if($user['active'] == 0){

              $this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact admin.");
            }
            */

            $payload = [
                'iat' => time(),
                'iss' => 'funus',
                'exp' => time() + (60*60),
                'userId' => $user['id']

            ];

            $token = JWT::encode($payload, SECRETE_KEY);

            $data = ['token' => $token];
            $this->returnResponse(SUCCESS_RESPONSE, $data);
          }catch(Exception $e){
            
            $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
          }
        }



        public function generateReferalCode($length = 5){

          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
          $charactersLength = strlen($characters);
          $redCode = '';

          for($i = 0; $i < $length; $i++){

            $redCode .= $characters[rand(0, $charactersLength - 1)];
          }

          return $redCode;

        }


        
        
        public function signUp(){

          $first_name = $this->validateParameters('first_name', $this->data['first_name'], STRING);
          $last_name = $this->validateParameters('last_name', $this->data['last_name'], STRING);
          $email_address = $this->validateParameters('email_address', $this->data['email_address'], STRING);
          $password = $this->validateParameters('password', md5($this->data['password']), STRING);
          $contact_number = $this->validateParameters('contact_number', $this->data['contact_number'], STRING);
          $signed_up_on = date('Y-m-d H:i:s');
          $subscribed_on = date('Y-m-d H:i:s');
          $expires_on = date('Y-m-d H:i:s');
          $plan = '0';
          $referee = $this->generateReferalCode();
          $referer = $this->data['referer'];
          
          
        

          $creator = new Creator;
          $creator->setFirstName($first_name);
          $creator->setLastName($last_name);
          $creator->setEmailAddress($email_address);
          $creator->setPassword($password);
          $creator->setContactNumber($contact_number);
          $creator->setSignedUpOn($signed_up_on);
          $creator->setSubscribedOn($subscribed_on);
          $creator->setExpiresOn($expires_on);
          $creator->setPlan($plan);
          $referee = !empty($referee) ? $referee : 'empty';
          $referer = !empty($referer) ? $referer : 'empty';
          
          $creator->setReferee($referee);
          $creator->setReferer($referer);
          

          
          try{

            if(!is_array($creator->getCreator())){

              if(!$creator->insert()){
                 
                 $message = "Failed to create user.";
              } else{

                 $message = "Inserted successfully.";
              }
            }else{
              $message = "Oops this user already exists, kindly login.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);

          }catch(Exception $e){
            
            $this->returnResponse(INVALID_USER, "This user is invalid, kindly contact memories ");
          }
          

        }


        public function editMemorial(){


          try{



            $deceased_name = $this->data['deceased_name'];
            $year_of_birth = $this->data['yob_yod'];
            $quote = $this->data['quote'];
            $life_story = $this->data['story'];
            $memorial_id = $this->data['edit_id'];
            $memorial_un_id = $this->data['edit_id'];

            $memorial = new Memorial;
            $memorial-> setDeceasedName($deceased_name);
            $memorial-> setYearOfBirth($year_of_birth);
            $memorial->setQuote($quote);
            $memorial->setLifeStory($life_story);
            $memorial->setMemorialId($memorial_id);
            $memorial->setMemorialUnId($memorial_un_id);

            
            /*
            $tribute = new Tribute;
            $tribute->setTributeFor($deceased_name);
            $tribute->setMemorialId($memorial_id);



            $media_upload = new MediaUpload;
            $media_upload->setDeceasedName($deceased_name);
            $media_upload->setMemorialId($memorial_id);
            */



            if(!$memorial->edit()){
                   
               $message = "Failed to edit memorial.";
            } else{

               $message = "Edited Memorial successfully.";
            }

            
            $this->returnResponse(SUCCESS_RESPONSE, $message);

          }catch(Exception $exception){

            $this->returnResponse(INVALID_USER, "".$exception->getMessage());
          }
        }


        public function receive_momo_request(){

          

          $payment_network = $this->data['payment_network'];
          $contact_number = $this->data['number'];
          $password = md5($this->data['password']);
          $amount = $this->data['amount'];
          $plan = $this->data['plan'];
          $creator_email = $this->data['creator_email'];

          //check if the person is a valid user.
          $creator = new Creator;
          $creator->setPassword($password);
          $creator->setContactNumber($contact_number);

          if(!is_array($creator->verifyCreatorForPayment())){

            $this->returnResponse(INVALID_USER, "This user is invalid, kindly contact funus ");

          }else{
            //$message = "This user exists";
            //$this->returnResponse(SUCCESS_RESPONSE, $message);

            //make curl here.
            $this->callBillingMachine($payment_network, $contact_number, $amount, $plan, $creator_email, "FUNUS SUBSCRIPTION");
          }

        }


        /*free subscription*/
        public function subscribe_free(){

          $creator_email = $this->data['creator_email'];
          $creator = new Creator;
          $creator->setEmailAddress($creator_email);
          $creator->setPlan('3');

          if(!is_array($creator->checkCreatorEmail())){

            $this->returnResponse(INVALID_USER, "This user is invalid, kindly contact funus or create an account");

          }else{

            //subscribe free plan and send success message.
            //$message = "This user exists";
            //$this->returnResponse(SUCCESS_RESPONSE, $message);

            
            if(!$creator->updateCreatorPlan()){
                   
               $message = "Failed to enroll for free plan.";
            } else{

               $message = "Subscribed to free plan successfully.";
            }

            
            $this->returnResponse(SUCCESS_RESPONSE, $message);
          }
        }


        /*recieve donation request*/

        public function receive_donation_request(){

          //$payment_network, $number, $amount, $donator_name, $deceased_name, $description

          $payment_network = $this->data['payment_network'];
          $momo_number = $this->data['number'];
          $amount = $this->data['amount'];
          $donator_name = $this->data['donator_name'];
          $deceased_name = $this->data['deceased_name'];

          

          //make curl here.
          $this->callDonationMachine($payment_network, $momo_number, $amount, $donator_name, "Donatiion for ".$deceased_name, $deceased_name);
          
        }

        /*end of recieve donation request*/


        public function callBillingMachine($payment_network, $number, $amount, $plan, $creator_email, $description){
          
           
          $receive_momo_request = array(
            'CustomerName' => 'Funus',
            'CustomerMsisdn'=> $number,//'0273206468',
            'CustomerEmail'=> 'info@mobilecontent.com.gh',
            'Channel'=> $payment_network,//'mtn-gh',
            'Amount'=> $amount,//0.01,
            'PrimaryCallbackUrl'=> 'https://funeral.myeventsgh.com/api/', //'http://louis.requestcatcher.com/',
            //'SecondaryCallbackUrl'=> 'http://requestb.in/1minotz1',
            'Description'=> $description,
            'ClientReference'=> '23214'
          );



          //API Keys

          $clientId = 'MQrOl8B';
          $clientSecret = 'bb2deaa043434aae8bfcb39569278a8d';
          $basic_auth_key =  'Basic ' . base64_encode($clientId . ':' . $clientSecret);
          //$request_url = 'https://api.hubtel.com/v1/merchantaccount/merchants/HM0102180009/receive/mobilemoney';
          $request_url = 'https://rmp.hubtel.com/merchantaccount/merchants/HM0102180009/receive/mobilemoney/';
          $receive_momo_request = json_encode($receive_momo_request);

          $ch =  curl_init($request_url);  
              curl_setopt( $ch, CURLOPT_POST, true );  
              curl_setopt( $ch, CURLOPT_POSTFIELDS, $receive_momo_request);  
              curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );  
              curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                  'Authorization: '.$basic_auth_key,
                  'Cache-Control: no-cache',
                  'Content-Type: application/json',
                ));

          $result = curl_exec($ch); 
          $err = curl_error($ch);
          curl_close($ch);

          if($err){
            echo $err;
          }else{

            $transactions_initiated = new TransactionInitiated;

            

            $json = json_decode($result, true);


            try{

              $transactions_initiated->setResponseCode($json['ResponseCode']);
              $transactions_initiated->setTransactionId($json['Data']['TransactionId']);
              $transactions_initiated->setDescription($json['Data']['Description']);
              $transactions_initiated->setClientReference($json['Data']['ClientReference']);
              $transactions_initiated->setAmount($json['Data']['Amount']);
              $transactions_initiated->setCharges($json['Data']['Charges']);
              $transactions_initiated->setAmountAfterCharges($json['Data']['AmountAfterCharges']);
              $transactions_initiated->setAmountCharged($json['Data']['AmountCharged']);
              $transactions_initiated->setDeliveryFee($json['Data']['DeliveryFee']);
              $transactions_initiated->setPaymentNetwork($payment_network);
              $transactions_initiated->setMomoNumber($number);
              $transactions_initiated->setCreatorEmail($creator_email);
              $transactions_initiated->setPlan($plan);
              $transactions_initiated->setTransactionDate(date("Y-m-d H:i:s"));
              $transactions_initiated->setMessage($json['Message']);
              $transactions_initiated->insert();

            }catch(Exception $e){

              echo $e;

            }  
          }
        }




        /*donation machine*/


        public function callDonationMachine($payment_network, $number, $amount, $donator_name,  $description, $deceased_name){
          
           
          $receive_momo_request = array(
            'CustomerName' => 'Funus',
            'CustomerMsisdn'=> $number,//'0273206468',
            'CustomerEmail'=> 'info@mobilecontent.com.gh',
            'Channel'=> $payment_network,//'mtn-gh',
            'Amount'=> $amount,//0.01,
            'PrimaryCallbackUrl'=> 'https://funeral.myeventsgh.com/api/', //'http://louis.requestcatcher.com/',
            //'SecondaryCallbackUrl'=> 'http://requestb.in/1minotz1',
            'Description'=> $description,
            'ClientReference'=> '23215'
          );



          //API Keys

          $clientId = 'MQrOl8B';
          $clientSecret = 'bb2deaa043434aae8bfcb39569278a8d';
          $basic_auth_key =  'Basic ' . base64_encode($clientId . ':' . $clientSecret);
          //$request_url = 'https://api.hubtel.com/v1/merchantaccount/merchants/HM0102180009/receive/mobilemoney';
          $request_url = 'https://rmp.hubtel.com/merchantaccount/merchants/HM0102180009/receive/mobilemoney/';
          $receive_momo_request = json_encode($receive_momo_request);

          $ch =  curl_init($request_url);  
              curl_setopt( $ch, CURLOPT_POST, true );  
              curl_setopt( $ch, CURLOPT_POSTFIELDS, $receive_momo_request);  
              curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );  
              curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                  'Authorization: '.$basic_auth_key,
                  'Cache-Control: no-cache',
                  'Content-Type: application/json',
                ));

          echo $result = curl_exec($ch); 
          $err = curl_error($ch);
          curl_close($ch);

          if($err){
            echo $err;
          }else{

            $donations_initiated = new DonationInitiated;

            

            $json = json_decode($result, true);


            try{

              $donations_initiated->setResponseCode($json['ResponseCode']);
              $donations_initiated->setTransactionId($json['Data']['TransactionId']);
              $donations_initiated->setDescription($json['Data']['Description']);
              $donations_initiated->setClientReference($json['Data']['ClientReference']);
              $donations_initiated->setAmount($json['Data']['Amount']);
              $donations_initiated->setCharges($json['Data']['Charges']);
              $donations_initiated->setAmountAfterCharges($json['Data']['AmountAfterCharges']);
              $donations_initiated->setAmountCharged($json['Data']['AmountCharged']);
              $donations_initiated->setDeliveryFee($json['Data']['DeliveryFee']);
              $donations_initiated->setPaymentNetwork($payment_network);
              $donations_initiated->setMomoNumber($number);
              $donations_initiated->setDonatorName($donator_name);
              $donations_initiated->setDonationFor($deceased_name);
              $donations_initiated->setTransactionDate(date("Y-m-d H:i:s"));
              $donations_initiated->setMessage($json['Message']);
              $donations_initiated->insert();

            }catch(Exception $e){

              echo $e;

            }  
          }
        }

        /*end of donation machine*/



        
        /*subscription callback*/
        public function callback(){
          //echo $this->data['Data']['AmountAfterCharges'];
          /*
          $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
          $txt = $this->data['ResponseCode'];
          fwrite($myfile, $txt);
          */
          
          
          
          try{


            $transactions_initiated = new TransactionInitiated;
            $transactions_initiated->setTransactionId($this->data['Data']['TransactionId']);
            $result = $transactions_initiated->getSubscriber();



            

            //now make insertions into transaction completed table.
            
            $transaction_completed = new TransactionCompleted;
            $transaction_completed->setResponseCode($this->data['ResponseCode']);
            $transaction_completed->setAmountAfterCharges($this->data['Data']['AmountAfterCharges']);
            $transaction_completed->setTransactionId($this->data['Data']['TransactionId']);
            $transaction_completed->setClientReference($this->data['Data']['ClientReference']);
            $transaction_completed->setDescription($this->data['Data']['Description']);
            $transaction_completed->setExternalTransactionId($this->data['Data']['ExternalTransactionId']);
            $transaction_completed->setAmount($this->data['Data']['Amount']);
            $transaction_completed->setCharges($this->data['Data']['Charges']);
            $transaction_completed->setCreatorEmail($result['creator_email']);
            $transaction_completed->setMomoNumber($result['momo_number']);
            $transaction_completed->setPlan($result['plan']);
            $transaction_completed->setTransactionDate(date("Y-m-d H:i:s"));
            $transaction_completed->insert();

            //now update subscribers plan
            
          }catch(Exception $e){

            $this->returnResponse("ERROR_RESPONSE", $e->getMessage());

          }
            
        }
        /*end of subscription callback*/





        /*donation callback*/

        public function donationcallback(){
          //echo $this->data['Data']['AmountAfterCharges'];
          /*
          $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
          $txt = $this->data['ClientReference'];
          fwrite($myfile, $txt);
          */
          
          
          
          try{

            $donations_initiated = new DonationInitiated;
            $donations_initiated->setTransactionId($this->data['Data']['TransactionId']);
            $result = $donations_initiated->getSubscriber();

            
            

            //now make insertions into donations completed table.
            
            $donation_completed = new DonationCompleted;
            $donation_completed->setResponseCode($this->data['ResponseCode']);
            $donation_completed->setTransactionId($this->data['Data']['TransactionId']);
            $donation_completed->setDescription($this->data['Data']['Description']);
            $donation_completed->setClientReference($this->data['Data']['ClientReference']);
            $donation_completed->setAmount($this->data['Data']['Amount']);
            $donation_completed->setCharges($this->data['Data']['Charges']);
            $donation_completed->setAmountAfterCharges($this->data['Data']['AmountAfterCharges']);
            $donation_completed->setMomoNumber($result['momo_number']);
            $donation_completed->setTransactionDate(date("Y-m-d H:i:s"));
            $donation_completed->setMessage($this->data['Message']);
            $donation_completed->setDonatorName($result['donator_name']);
            $donation_completed->setDonationFor($result['donation_for']);

            $donation_completed->setExternalTransactionId($this->data['Data']['ExternalTransactionId']);
            
            
            $donation_completed->insert();
            

            //now update subscribers plan
            
          }catch(Exception $e){

            echo $e->getMessage();

            //$this->returnResponse("ERROR_RESPONSE", $e->getMessage());

          }
          
            
        }

        /*end of donation callback*/


    
        //this is a function for signnin
        public function signIn(){

          $email_address = $this->validateParameters('email_address', $this->data['email_address'], STRING);

          $password = $this->validateParameters('password', md5($this->data['password']), STRING);

          $creator = new Creator;
          $creator->setEmailAddress($email_address);
          $creator->setPassword($password);
          $creator = $creator->getCreator();
          



          try{

            /*

            $stmt = $this->dbConn->prepare("SELECT * FROM creators WHERE email_address = :email_address AND password = :password");
             
            $stmt->bindParam(":email_address", $email_address);
            
            $stmt->bindParam(":password", $password);

            $stmt->execute();

            $creator = $stmt->fetch(PDO::FETCH_ASSOC);
            */

            if(!is_array($creator)){

              $message = "User does not exist";

              $this->returnResponse(INVALID_USER, $message);

            }



            /*
            $payload = [
                'iat' => time(),
                'iss' => 'funus',
                'exp' => time() + (60*60),
                'userId' => $creator['id']

            ];

            $token = JWT::encode($payload, SECRETE_KEY);
            */
            
            $message = "success";

            //$data = ['message'=> $message, /*'token'=> $token, 'payload' => $creator*/];
            $this->returnResponse(SUCCESS_RESPONSE, $message);



          }catch(Exception $e){
            
            $this->returnResponse(INVALID_USER, "This user is invalid, kindly contact ustream");
          }    
        }






        public function getCreatorFirstName(){

          $email_address = $this->validateParameters('email_address', $this->data['email_address'], STRING);
          $creator = new Creator;
          $creator->setEmailAddress($email_address);
          $creator = $creator->getCreatorFirstName();

          if(!is_array($creator)){

            $message = "User does not exist";

            $this->returnResponse(INVALID_USER, $message);

          }

           $this->returnResponse(SUCCESS_RESPONSE, $creator);

        }


        public function createTribute(){

          $tribute_message = $this->validateParameters('tribute_message', $this->data['tribute_message'], STRING);
          $tribute_by = $this->validateParameters('tribute_by', $this->data['tribute_by'], STRING);
          $tribute_for = $this->validateParameters('tribute_for', $this->data['tribute_for'], STRING);
          $tribute_date = Date('Y-m-d H:i:s');
          $memorial_un_id = $this->validateParameters('deceased_code', $this->data['deceased_code'], STRING);

          $tribute = new Tribute;
          $tribute->setTributeMessage($tribute_message);
          $tribute->setTributeBy($tribute_by);
          $tribute->setTributeFor($tribute_for);
          $tribute->setTributeDate($tribute_date);
          $tribute->setMemorialUnId($memorial_un_id);
        

          try{
            if(!$tribute->insert()){
               
               $message = "Failed to create user.";
            } else{

               $message = "Inserted successfully.";

            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
          }catch(Exception $e){
            echo $e->getMessage();
          }


        }
        


       



        





        

        public function contribute(){

          $campaigne_id = $this->validateParameters('campaigne_id', $this->data['campaigne_id'], INTEGER);
          $contributor_number = $this->validateParameters('contributor_number', $this->data['contributor_number'], STRING);
          $contributor_amount = $this->validateParameters('contributor_amount', $this->data['contributor_amount'], STRING);

          $contributor = new Contributor;
          $contributor->setCampaigneId($campaigne_id);
          $contributor->setContributorNumber($contributor_number);
          $contributor->setContributorAmount($contributor_amount);

          try{

            if(!$contributor->insert()){

              $message = "Oops unable to make contribution";
                  
            }else{
              $message = "Success";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);

          }catch(Exception $e){

            $this->returnResponse("ERROR", "".$e->getMessage());

          }

        }



        //this gets all the join requests for a particular user.
        public function getMyContributors(){
          $campaigne_id = $this->validateParameters('campaigne_id', $this->data['campaigne_id'], INTEGER);

          $contributors = new Contributor();
          $contributors->setCampaigneId($campaigne_id);
          $contributors = $contributors->getMyContributors();

          try{

            if(!is_array($contributors)){

              $message = "No Contributors found.";

              $this->returnResponse(INVALID_USER, $message);
            }

            $message = "contributors found";
            $data = ['contributors' => $contributors, 'message'=> $message];

            $this->returnResponse(SUCCESS_RESPONSE, $data);

          }catch(Exception $e){

            $this->returnResponse("ERROR", "".$e->getMessage());
          }
          

          

        }




        public function pay(){

          $channel = $this->validateParameters('channel', $this->data['channel'], STRING);
          $number = $this->validateParameters('number', $this->data['number'], STRING);
          $amount = $this->validateParameters('amount', $this->data['amount'], STRING);
          $product_name = $this->validateParameters('product_name', $this->data['product_name'], STRING);
          $email = $this->validateParameters('email', $this->data['email'], STRING);

          $service = new MobiPayService;
          $service->setEmail($email);
          $service->setProductName($product_name);
          $service = $service->getServiceInfo();



          try{



            if(!is_array($service)){

              $message = "Permission denied, this service is not authenticated";

              $this->returnResponse(SUBSCRIPTION_ERROR, $message);

            }
             
            $token = $this->getBearerToken();
            $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);

            $stmt = $this->dbConn->prepare("SELECT * FROM mobipay_users WHERE user_id = :userId");
             
            $stmt->bindParam(":userId", $payload->userId);
            

            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!is_array($user)){

              $this->returnResponse(INVALID_USER_PASS, "This user is not found.");
            }


            //print_r($payload->userId);
            

            //call hubtel
            $hubtel = new hubtelPay;
            $hubtel->receive_momo_request($channel, $number, $amount, $product_name);

          }catch(Exception $e){

            $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
          }
        }




        /*
        public function validateSubscription(){

          $phone_number = $this->validateParameters('phone_number', $this->data['phone_number'], STRING);
          $content_name = $this->validateParameters('content_name', $this->data['content_name'], STRING);



          try{
             
            $token = $this->getBearerToken();
            $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);

            $stmt = $this->dbConn->prepare("SELECT * FROM ustream_users WHERE user_id = :userId");
             
            $stmt->bindParam(":userId", $payload->userId);
            

            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!is_array($user)){

              $this->returnResponse(INVALID_USER_PASS, "This user is not found.");
            }


            //print_r($payload->userId);
            

            //check if this subscriber has paid for this video?
            $stmt = $this->dbConn->prepare("SELECT `number`, `content_name` FROM track_pay WHERE `number` = :phone_number AND `content_name` = :content_name AND `transaction_status` = 'processed' ");
             
            $stmt->bindParam(":phone_number", $phone_number);
            $stmt->bindParam(":content_name", $content_name);

            

            $stmt->execute();

            $paid = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!is_array($paid)){

              $this->returnResponse(INVALID_USER_PASS, "You havent subscribed for this content, please pay to watch this content.");
            }else{
               $this->returnResponse(SUCCESS_RESPONSE, "paid");
            }

          }catch(Exception $e){

            $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
          }
        }
        */
        
        
   }
?>