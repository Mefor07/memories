<?php
    require_once('constants.php');
    
    class Rest{

        protected $request;
        protected $serviceName;
        protected $data;

    	public function __construct(){
           
           if($_SERVER['REQUEST_METHOD'] !== 'POST'){
              $this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method not valid');
           }
           
           $handler = fopen('php://input', 'r');
           $this->request = stream_get_contents($handler);
           $this->validateRequest();
    	}

    	public function validateRequest(){
            if($_SERVER['CONTENT_TYPE'] !== 'application/json'){

                  /*content type catcher*/
                 //$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
                 
                 //fwrite($myfile, $_SERVER['CONTENT_TYPE']);
                if($_SERVER['CONTENT_TYPE'] !== 'application/json; charset=utf-8'){ 

                  $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid');

                }
            }

            $this->data = json_decode($this->request, true);

            
            if(!isset($this->data['service']) || $this->data['service'] == ""){

                
                if(isset($this->data['ResponseCode'])){

                  //means this request is coming from hubtel, now determine wh

                  if($this->data['Data']['ClientReference'] == '23214'){

                    $this->data['service'] = 'callback';

                  }elseif($this->data['Data']['ClientReference'] == '23215'){

                    $this->data['service'] = 'donationcallback';
                    
                  }


                }else{
                   $this->throwError(API_NAME_REQUIRED, "API or service name is required.");
                }
                
                //$this->throwError(API_NAME_REQUIRED, $this->data['Data']['AmountAfterCharges']);
                
            }

            $this->serviceName = $this->data['service'];

            if($this->serviceName == 'signUp'){

              if(!isset($this->data['first_name']) || $this->data['first_name'] == ""

                || !isset($this->data['last_name']) || $this->data['last_name'] == ""

                || !isset($this->data['email_address']) || $this->data['email_address'] == ""

                || !isset($this->data['password']) || $this->data['password'] == ""

                || !isset($this->data['contact_number']) || $this->data['contact_number'] == ""){

                  $this->throwError(API_PARAM_REQUIRED, "field(s) cannot be empty. All fields are required.");

              }

            }elseif($this->serviceName == 'signIn'){

              if(!isset($this->data['email_address']) || $this->data['email_address'] == ""

                || !isset($this->data['password']) || $this->data['password'] == "" ){

                   $this->throwError(API_PARAM_REQUIRED, "field(s) cannot be empty. All fields are required.");
              }
            }

            /*

            if(!is_array($data['param'])){
                $this->throwError(API_PARAM_REQUIRED, "API PARAM is required.");
            }
            $this->param = $data['param'];
            */
    	}


    	public function processApi(){
         $api = new API;
         $rMethod = new reflectionMethod('API', $this->serviceName);

         if(!method_exists($api, $this->serviceName)) {

            $this->throwError(API_DOES_NOT_EXIST, "API does not exist");
         }

         $rMethod->invoke($api);
    	}

    	public function validateParameters($fieldName, $value, $dataType, $required = true){
           
            if($required == true && empty($value) == true){
             $this->throwError(VALIDATE_PARAMETER_REQUIRED, $fieldName." Parameter is required.");
            }
             
            switch ($dataType) {
              case BOOLEAN:
                if(!is_bool($value)){
                  $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for ". $fieldName.'. It should be boolean');
                }
                break;

                case INTEGER:
                if(!is_numeric($value)){
                  $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for ". $fieldName.'. It should be numeric');
                }
                break;

                case STRING:
                if(!is_string($value)){
                  $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for ". $fieldName. ' It should be string');
                }
                break;


                case ARN:
                if(!is_array($value)){
                  $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for ". $fieldName. ' It should be string');
                }
                break;
              
              default:
                $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for ". $fieldName);
                break;
            }

            return $value;
    	}

        /* Get header Authorization
        * */
        public function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }


        /**
         * get access token from header
         * */
        public function getBearerToken() {
            $headers = $this->getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            $this->throwError( ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
        }



    	public function throwError($code, $message){
           header("content-type: application/json");
           $errorMsg = json_encode(["error"=>['status'=>$code, 'message'=>$message]]);
           echo $errorMsg; exit;
    	}

    	public function returnResponse($code, $data){
            header("content-type: application/json");
            $response = json_encode(['response'=>['status'=>$code, 'result' => $data]]);
            echo $response; exit;
    	}
    }
?>