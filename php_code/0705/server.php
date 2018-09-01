<?php
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'127.0.0.1',9000);
socket_listen($socket,5);
$client = socket_accept($socket);

while(true)

{	if(socket_last_error())
	{
		socket_close($client);
		break;
	}
	$buf = socket_read($client,1024);
	echo $buf;
	if(preg_match("/GET\s\/(.*?)\sHTTP\/1.1/i",$buf,$matches))
	{
		$page_path = $matches[1];
		if(file_exists($page_path))
		{
			$html_content = file_get_contents($page_path);
			//socket_write($client,$html_content);
			$str = "HTTP/1.1 200 OK\r\nConnection: keep-alive\r\nServer: workerman\1.1.4\r\n\r\nhello";
			socket_send$client,$str,100,MSG_DONTROUTE);
		}else{
			socket_write($client,'404');
		}
	}
	if(!$buf)
	{
		socket_close($client);
		break;
	}
	socket_write($client,'hello socket');
//	socket_close($client);
}
socket_close($socket);
