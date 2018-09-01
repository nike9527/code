<?php
class smtp_mail
{
	private $host;
	private $port = 25;
	private $user;
	private $pass;
	private $debug = false;
	private $sock;
	private $mail_format = 0;

	public function __construct($host,$port,$user,$pass,$formate=1,$debug=0){
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
		$this->mail_format = $formate;
		$this->debug = $debug;
		$this->sock = fsockopen($this->host,$this->port,&$errno,&$errstr,10);
		!($this->sock) or exit("Error number: $error,Error message: $errstr".PHP_EOL);
		$response = fgets($this->sock);
		if(strstr($response,"220") === false){
			exit("server error:$response");
		}
	}

	private function show_debug($message){
		if($this->debug) 
			echo "<p>Debug:$message</p>";
	}
	private function do_command($cmd,$return_code){
		fwrite($this->sock,$cmd);
		$response = fgets($this->sock);
		if(strstr($response,"$return_code") === false)
		{
			$this->show_debug($response);
			return false;
		}
		return true;
	}
	private function is_email($email){
		$pattren = "/^[^_][\w]*@[\w.]+[\w]*[^_]$/";
		if(preg_match($pattren,$email,$matches){
			return true;
		}else{
			return false;
		}
	}
	public function send_mail($from,$to,$subject,$body){
		if(!$this->is_email($from) or $this->is_email($to))
		{
			$this->show_debug("Please enter vaild from/to email");
			return false;
		}
		if(empty($subject) or empty($body))
		{
			$this->show_debug("please enter subject/content.");
			return false;
		}
		$detail = "From: $from\r\n";
		$detail .= "to:$to\r\n";
		$detail .= "subject:$subject\r\n";
		$detail .="charset=gb2312\r\n\r\n";
		$detail .=$body;
		if($this->mail_format == 1)
		{
			$detal .="Content-Type:text/html;\r\n";
		}else{
				
			$detal .="Content-Type:text/plain;\r\n";
		}
	}
	$this->do_command("HELLO smtp.qq.com\r\n",255);
	$this->do_command("AUTH LOGIN\r\n",334);
	$this->do_command($this->user."\r\n",334);
	$this->do_command($this->pass."\r\n",235);
	$this->do_command("MALL FROM:<$from>\r\n",250);
	$this->do_command("RCPT TO:<$to>\r\n",250);
	$this->do_command("DATA:\r\n",354);
	$this->do_command("$detail\r\n",250);
	$this->do_command("QUIT\r\n",221);
}
