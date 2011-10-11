<?php
include_once("class.DB.php");

class Settings extends DBManager{
	
	var $table;
	
	public function __construct(){
		$this->table = "__TABLE__";
	}
	
	public function getSetting($field){
		$data = array("field"=>$field);
		return $this->getRowValue($data);
	}
	
	public function updateSetting($field,$value){
		$data = array("field"=>$field);
		$cond = array("value"=>$value);
		if($this->update($data,$cond)){
			return true;
		}else{
			return false;
		}
	}
}
?>