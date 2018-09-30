<?php
$servsock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($servsock,'192.168.199.182',9003);
socket_listen($servsock,128);
$read_sock = [];
$write_sock = [];
$except_sock = NULL;
$read_socks[] = $servsock;
while(1)
{
	$tmp_reads = $read_socks;
	$tmp_writes = $write_sock;
	$count = socket_select($tmp_reads, $tmp_writes, $except_sock, NULL);
	foreach ($tmp_reads as $read) {
		if($read == $servsock)
		{
			$connsock = socket_accept($servsock);
			if($connsock)
			{
				socket_getpeername($connsock, $addr,$port);
				echo "client connect server:ip = $addr,port=$port".PHP_EOL;
				$read_socks[] = $connsock;
				$write_sock[] = $connsock;
			}
		}else{
			$data = socket_read($read, 1024);
			if($data === ''){
				foreach ($read_socks as $key => $value) {
					if($value == $read) unset($read_socks[$key]);
				}
				foreach ($write_sock as $key => $value) {
					if($value==$read) unset($write_sock[$key]);
				}
				socket_close($read);
				echo "client close".PHP_EOL;
			}else{
				socket_getpeername($read, $addr, $port);
				echo "read from client # $addr:$port#".$data.PHP_EOL;
				$response = "HTTP/1.1 200 OK\r\n";
                $response .= "Server: phphttpserver\r\n";
                $response .= "Content-Type: text/html\r\n";
                $response .= "Content-Length: 3\r\n\r\n";
                $response .= "ok\n";
				//$data = strtoupper($data);
				if(in_array($read, $tmp_writes)){
					//socket_write($read, $data);
					 //如果该客户端可写 把数据回写给客户端
                    socket_write($read, $response);
                    //socket_close($read);  // 主动关闭客户端连接
                    //移除对该 socket 监听
                    foreach ($read_socks as $key => $val)
                    {
                        if ($val == $read) unset($read_socks[$key]);
                    }
 
                    foreach ($write_sock as $key => $val)
                    {
                        if ($val == $read) unset($write_sock[$key]);
                    }
				}
			}

		}
	}
}
socket_close($servsock);