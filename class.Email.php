<?php
class Email{

	var $name;
	var $from;
	var $cc;
	var $template;
	var $body;
	var $subject;
	var $attach = array();
	
	public function setTemplate($template){
		$this->template = $template;
	}
	
	public function setSubject($subject){
		$this->subject = $subject;
	}
	
	public function setCC($cc){
		$this->cc = $cc;
	}
	
	public function setBody($body){
		$this->body = $body;
	}
	
	public function setFrom($name,$from){
		$this->name = $name;
		$this->from = $from;
	}
	
	public function prepareTemplate($search,$replace){
		$source = file_get_contents($this->template);
		$replace = str_replace($search,$replace,$source);
		$this->body = $replace;
	}
	
	public function addFile($file){
		$files = fopen($file, "r");
		$name = basename($file);
		$content = fread($files,filesize($file)); 
		$encoded_attach = chunk_split(base64_encode($content)); 
		$mime = mime_content_type($file);
		fclose($files);
		$return = array("file"=>$encoded_attach,"mime"=>$mime,"name"=>$name); 
		$this->attach = $return;
	}
		
	public function sendEmail($to){
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		$headers .= "From: ".$this->name."<".$this->from.">\r\n"; 
		$headers .= "Cc: ".$this->cc."\r\n"; 
		if(mail($to,$this->subject,$this->body,$headers)){
			return true;
		}else{
			return false;
		}
	}

	public function sendEmailWithAttachment($to){
		$random_hash = md5(date('r', time())); 
		$headers = "MIME-Version: 1.0\n"; 
		$headers .= "From: ".$this->name." <".$this->from.">\n"; 
		$headers .= "Cc: ".$this->cc."\n"; 
		$headers .= "Content-type: multipart/mixed; "; 
		$headers .= "boundary=\"Message-Boundary\"\n"; 
		$headers .= "Content-transfer-encoding: 7BIT\n";
		$headers .= "X-attachments: ".$this->attach["name"]."\n";
		$body_top = "--Message-Boundary\n"; 
		$body_top .= "Content-type: text/html; charset=iso-8859-1\n"; 
		$body_top .= "Content-transfer-encoding: 7BIT\n"; 
		$body_top .= "Content-description: Body\n\n";
		
		$body = $body_top.$this->body;
		
		$body .= "\n\n--Message-Boundary\n"; 
		$body .= "Content-type: Binary; name=\"".$this->attach["name"]."\"\n"; 
		$body .= "Content-Transfer-Encoding: BASE64\n"; 
		$body .= "Content-disposition: attachment; filename=\"".$this->attach["name"]."\"\n"; 
		$body .= "".$this->attach["file"]."\n"; 
		$body .= "--Message-Boundary--\n"; 


		if(mail($to,$this->subject,$body,$headers)){
			return true;
		}else{
			return false;
		}
	}
}		
?>