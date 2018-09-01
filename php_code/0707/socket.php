<?php
$sock = fsockopen("192.168.199.182","9001",$error,$errstr,1);
if(!$sock)
{
	echo "$errstr ($error)<br/> \n";
}else{
	fwrite($sock,"end\r\n");
	while(!feof($sock)){
		echo fread($sock,128);
		flush();
		ob_flush();
		sleep(1);
	}
	fclose($sock);
}
