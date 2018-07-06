<?php
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'127.0.0.1',9000);
socket_listen($socket,5);
//$client = socket_accept($socket);

while(true)
{	
	
	$client = socket_accept($socket);
	while(true)
	{
		if(socket_last_error())
		{
			socket_close($client);
			break;
		}
		$buf = socket_read($client,1024);
		echo $buf;
		if(!$buf)
		{
			socket_close($client);
			break;
		}
		socket_write($client,'hello socket');
	}
//	socket_close($client);
}
socket_close($socket);
