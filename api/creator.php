<?php

class Creator{

	private $id;
	private $first_name;
	private $last_name;
	private $email_address;
	private $password;
	private $contact_number;
	private $signed_up_on;
	private $subscribed_on;
	private $expires_on;
	private $plan;
	private $referee;
	private $referer;
	private $tableName = 'creators';



	public function setId($id) { $this->id = $id; }
	public function getId(){ return $this->id; }

	public function setFirstName($first_name){
		$this->first_name = $first_name;
	}

	public function getFirstName(){ 
		return $this->first_name;
	}

	public function setLastName($last_name){
		$this->last_name = $last_name;
	}

	public function getLastName(){ 
		return $this->last_name;
	}

	public function setEmailAddress($email_address){
		$this->email_address = $email_address;
	}

	public function getEmailAddress(){ 
		return $this->email_address;
	}

	public function setPassword($password){
		$this->password = $password;
	}


	public function setContactNumber($contact_number){
		$this->contact_number = $contact_number;
	}

	public function getContactNumber(){ 
		return $this->contact_number;
	}

	public function setSignedUpOn($signed_up_on){

		$this->signed_up_on = $signed_up_on;
	}

	public function getSignedUpOn(){

		return $this->signed_up_on;
	}

	public function setSubscribedOn($subscribed_on){

		$this->subscribed_on = $subscribed_on;
	}

	public function getSubscribedOn(){

		return $this->subscribed_on;
	}


	public function setExpiresOn($expires_on){

		$this->expires_on = $expires_on;
	}

	public function getExpiresOn(){

		return $this->expires_on;
	}


	public function setPlan($plan){

		$this->plan = $plan;
	}

	public function getPlan(){

		return $this->plan;
	}


	public function setReferee($referee){

		$this->referee = $referee;
	}

	public function getReferee(){

		return $this->referee;
	}

	public function setReferer($referer){

		$this->referer = $referer;
	}

	public function getReferer(){

		return $this->referer;
	}


	public function __construct() {
		$db = new DbConnect();
		$this->dbConn = $db->connect();
	}




	public function insert() {

		$sql = 'INSERT INTO ' . $this->tableName . '(id, first_name, last_name, email_address, password, contact_number, signed_up_on, 
			subscribed_on, expires_on, plan, referee, referer) VALUES(null, :first_name, :last_name, :email_address, :password, :contact_number, :signed_up_on,
			:subscribed_on, :expires_on, :plan, :referee, :referer)';

        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email_address', $this->email_address);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':signed_up_on', $this->signed_up_on);
        $stmt->bindParam(':subscribed_on', $this->subscribed_on);
        $stmt->bindParam(':expires_on', $this->expires_on);
        $stmt->bindParam(':plan', $this->plan);
		$stmt->bindParam(':referee', $this->referee);
		$stmt->bindParam(':referer', $this->referer);

       

        if($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}




	public function getCreator(){
		$sql = 'SELECT `email_address` FROM '.$this->tableName.' WHERE email_address = :email_address ';
		$stmt = $this->dbConn->prepare($sql);
		$stmt->bindParam(':email_address', $this->email_address);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user;
	}

	public function getCreatorFirstName(){
		$sql = 'SELECT `first_name` FROM '.$this->tableName.' WHERE email_address = :email_address';
		$stmt = $this->dbConn->prepare($sql);
		$stmt->bindParam(':email_address', $this->email_address);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user;
	}


	public function checkCreator(){
		$sql = 'SELECT `email_address` FROM '.$this->tableName.' WHERE email_address = :email_address';
		$stmt = $this->dbConn->prepare($sql);
		$stmt->bindParam(':mobile_number', $this->mobile_number);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user;
	}


	public function verifyCreatorForPayment(){

		$sql = 'SELECT `contact_number`, `password` FROM '.$this->tableName.' WHERE contact_number = :contact_number AND password = :password';
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':password', $this->password);
		$stmt->execute();
		$creator = $stmt->fetch(PDO::FETCH_ASSOC);
		return $creator;

	}

	public function checkCreatorEmail(){
		$sql = 'SELECT `email_address` FROM '.$this->tableName.' WHERE email_address = :email_address';
		$stmt = $this->dbConn->prepare($sql);
		$stmt->bindParam(':email_address', $this->email_address);
		$stmt->execute();
		$creator = $stmt->fetch(PDO::FETCH_ASSOC);
		return $creator;
	}

	


	public function checkBarn(){
		$sql = 'SELECT `barned` FROM '.$this->tableName.' WHERE mobile_number = :mobile_number';
		$stmt = $this->dbConn->prepare($sql);
		$stmt->bindParam(':mobile_number', $this->mobile_number);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user;
	}


	public function updateCreatorPlan(){

		$sql = 'UPDATE '.$this->tableName.' SET plan = :plan WHERE email_address = :email_address';
		$stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':email_address', $this->email_address);
        $stmt->bindParam(':plan', $this->plan);
        if($stmt->execute()) {
			return true;
		} else {
			return false;
		}
		
	}
}
?>