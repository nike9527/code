<?php
error_reporting(E_ALL);
set_time_limit(0);
echo "<h2>TCP/IP Connection</h2>";
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
echo "³¢ÊÔÁ¬½Ó¡£¡£¡£¡£";
$in = "Ho".PHP_EOL;
$in .= "first blood".PHP_EOL;
$out = "";
$result = socket_connect($socket,"192.168.199.182",9006);
while(1){
   	echo "=====================================".PHP_EOL; 
	socket_write($socket,$in,strlen($in));
   	echo "=====================================".PHP_EOL; 
	$out = socket_read($socket,8192);
	echo "accept msage ".$out.PHP_EOL;
}
echo "close...";
socket_close($socket);
echo "ok".PHP_EOL;
?>
