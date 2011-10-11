<?php
class DBManager{
		var $dbhost;
		var $dbuser;
		var $dbpass;
		var $dbname;
		var $table;
		
	public function __construct($dbhost,$dbuser,$dbpass,$dbname){
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
	}
	
	public function setTable($table){
		$this->table = $table;
	}
	
	public function connect(){
		$link  = mysql_connect($this->dbhost,$this->dbuser,$this->dbpass);
		if($link){
			mysql_select_db($this->dbname,$link) or die(mysql_error());
		}else{
			die(mysql_error());
		}
	}
	
	
	public function insert($data){
		$append = "";
		$prepend = "";
		$args = count($data);
		$i = 0;
		$m = $args-1;
		foreach($data as $field=>$value){
			$k = $i++;
			switch($k){
				case 0: 
					$append .= "(".$field;
					$prepend .= "('".$value."'";
				break;
				
				case $m:
					$append .= ",".$field.")";
					$prepend .= ",'".$value."')";
				break;
				
				default:
					$append .= ",".$field;
					$prepend .= ",'".$value."'";
				break;
			}
		}
		$query = "INSERT INTO ".$this->table." ".$append." VALUES ".$prepend;
		if(mysql_query($query)){
			return true;
		}else{
			die(mysql_error());
			return false;
		}
	}
	
	public function delete($data){
		$add = "";
		$args = count($data);
		$i = 0;
		$m = $args-1;
		foreach($data as $field=>$value){
			$k = $i++;
			switch($k){
			
				case $m:
					$add .= $field." = '".$value."'";
				break;
				
				default:
					$add .= $field." = '".$value."' AND";
				break;
			}
		}
		$query = "DELETE * FROM ".$this->table." WHERE ".$add;
		if(mysql_query($query)){
			return true;
		}else{
			die(mysql_error());
			return false;
		}
	}
	
	public function update($data,$cond){
		$add = '';
		$add2 = '';
		$total1 = count($data);
		$total2 = count($cond);
		$m1 = $total1-1;
		$m2 = $total2-1;
		$i = 0;
		$n = 0;
		foreach($data as $field=>$value){
			$k = $i++;
			switch ($k){
				case 0:
					$add .= $field." = '".$value."'";
				break;
		
				default:
					$add .= ", ".$field." = '".$value."'";
				break;
			}
		}
		foreach($cond as $fieldd=>$valuee){
			$k = $n++;
			switch($k){
				case $m2:
					$add2 .= $fieldd." = '".$valuee."'";
				break;
			
				default:
					$add2 .= $fieldd." = '".$valuee."' AND ";
				break;
			}
		}
		$query = "UPDATE ".$this->table." SET ".$add." WHERE ".$add2;
		if(mysql_query($query)){
			return true;
		}else{
			die(mysql_error());
			return false;
		}
	}
	
	public function getRow($data==NULL){
		if($data != NULL){
			$i = 0;
			$m = count($data)-1;
			$add = "";
			foreach($data as $field=>$value){
				$k = $i++;
				switch($k){
			
					case $m:
						$add .= $field." = '".$value."'";
					break;
				
					default:
					$add .= $field." = '".$value."' AND";
					break;
				}
			}
			$where = " WHERE ".$add;
		}else{
			$where = "";
		}
		$query = "SELECT * FROM ".$this->table.$where;
		$result = mysql_query($query);
		if($result){
			$row = mysql_fetch_array($result);
			return $row;
		}else{
			die(mysql_error());
			return false;
		}

	}
	
	public function getRows($data==NULL){
	if($data != NULL){
			$i = 0;
			$m = count($data)-1;
			$add = "";
			foreach($data as $field=>$value){
				$k = $i++;
				switch($k){
			
					case $m:
						$add .= $field." = '".$value."'";
					break;
				
					default:
					$add .= $field." = '".$value."' AND";
					break;
				}
			}
			$where = " WHERE ".$add;
		}else{
			$where = "";
		}
		$query = "SELECT * FROM ".$this->table.$where;
		$result = mysql_query($query);
		$array = array();
		if($result){
			while($row = mysql_fetch_array($result)){
				$id = $row["id"];
				$array[$id] = array();
				foreach($row as $key=>$value){
					$array[$id][$key] = $value;
				}
			}		
		}
		}else{
			die(mysql_error());
			return false;
		}
	}
	
	
	public function checkRow($data){
		$i = 0;
		$m = count($data)-1;
		$add = "";
		foreach($data as $field=>$value){
			$k = $i++;
			switch($k){
			
				case $m:
					$add .= $field." = '".$value."'";
				break;
				
				default:
					$add .= $field." = '".$value."' AND";
				break;
			}
		}
		$query = "SELECT * FROM ".$this->table." WHERE ".$add;
		$result = mysql_query($query);
		if($result){
			if(mysql_num_rows($result) > 0){
				return true;
			}else{
				return false;
			}
		}else{
			die(mysql_error());
			return false;
		}
	}
}

?>