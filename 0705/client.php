<?php
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_connect($socket,'127.0.0.1',9000);
while(true)
{
	socket_write($socket,'I am client');
	if(socket_last_error())
	{
		socket_close($socket);
		break;
	}
	$buf = socket_read($socket,1024);
	echo $buf.PHP_EOL;
}
socket_close($socket);
