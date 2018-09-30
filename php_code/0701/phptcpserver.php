<?php
$serversocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($serversocket,"192.168.199.182",9006);
socket_listen($serversocket,128);
while(1)
{
	$connsock = socket_accept($serversocket);
	socket_getpeername($connsock,$addr,$port);
	echo "==================client connect server ip=$addr:$port===============".PHP_EOL;
	while(1)
	{
		$data = socket_read($connsock,1024);
		if($data === "")
		{
			socket_close($connsock);
			echo "client close".PHP_EOL;
			break;
		}else{

			echo "read from client:".$data.PHP_EOL;
			$data = strtoupper($data);
			socket_write($connsock,$data);
		}
	}
}
socket_close($serversocket);
