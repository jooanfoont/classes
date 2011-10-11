<?php
include_once('class.DB.php');
class Users extends DBManager{

	var $table;

	public function __construct(){
		$this->table = "__TABLE__";
	}
	
	public function addUser($user,$pass,$email,$telf,$name,$type,$class,$active,$nhotels){
		$md5 = md5($pass);
		$data = array("user"=>$user,"pass"=>$md5,"email"=>$email,"telf"=>$telf,"name"=>$name,"type"=>$type,"class"=>$class,"active"=>$active,"nhotels"=>$nhotels);
		if($this->insert($data)){
			return true;
		}else{
			return false;
		}
	}
	
	public function checkUser($user){
		$data = array("user"=>$user);
		if($this->checkRow($data)){
			return true;
		}else{
			return false;
		}
	}
	
	public function getUser($field,$value){
		$data = array($field=>$value);
		if($this->getRow($data)){
			return $this->getRow($data);
			return true;
		}else{
			return false;
		}
	}
	
	public function getUsers($cond=NULL){	
		if($cond == NULL){
			$data = "";
		}else{
			$data = $cond;
		}
		$result = $this->getRows($data);
		if($result){
			return $result;
			return true;
		}else{
			return false;
		}
	}		
	
	public function changePassword($user,$pass){
		$md5 = md5($pass);
		$data = array("pass"=>$md5);
		$cond = array("user"=>$user);
		if($this->update($data,$cond)){
			return true;
		}else{
			return false;
		}
	}
}
?>