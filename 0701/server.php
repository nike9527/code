<?php 
set_time_limit(0);
$ip = "192.168.199.182";
$port = "9006";
$sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
$res = socket_bind($sock,$ip,$port);
socket_listen($sock,4);
$count = 0;
$msgsock = socket_accept($sock);
while(true)
{
//	$msgsock = socket_accept($sock);
	$msg = "server test success".PHP_EOL;
	socket_write($msgsock,$msg);

	echo "==============server test success===========".PHP_EOL;
	$buf = socket_read($msgsock,8192);

	echo "=============clien accept msgage============".PHP_EOL;
	echo "clien accpet Msage: ".$buf.PHP_EOL;

	if(++$count >= 5)
	{
		break;
	}
//	socket_close($msgsock);
}
socket_close($sock);
?>
