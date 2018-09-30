<?php
$post_ = array('author'=>'zz','mail'=>'zz@163.com','url'=>'','text'=>'test');
$data = http_build_query($post_);
$fp = stream_socket_client("tcp://typecho.org:80",$errno,$errstr,5);
$out = "POST http://typecho.org/archives/54/comment HTTP/1.1\r\n";
$out .= "Host: typecho.org\r\n";
$out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; zh - CN; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13"."\r\n";
$out .= "Content-type: application/x-www-form-urlencoded\r\n";
$out .= "Referer: http://typeche.org/archives/54/\r\n";
$out .= "PHPSESSID=082b0cc33cc7e6df1f87502c456c3eb0\r\n";
$out .= "Connection: close\r\n\r\n";
$out .= "$data\r\n\r\n";
fwrite($fp,$out);
while(!feof($fp))
{
	echo fgets($fp,1280);
}
fclose($fp);
