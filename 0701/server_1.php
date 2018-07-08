<?php
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'192.168.199.182',9006) || die('error');
socket_listen($socket,5);
$child = 0;
$i = 0;
$client = socket_accept($socket);
while(true){
	$i++;
//	$client = socket_accept($socket);
	$pid = pcntl_fork();
	socket_write($client,"this is server Pid=".$pid.PHP_EOL);
	socket_write($client,"i=".$i.PHP_EOL);
	error_log("this is server Pid=".$pid.PHP_EOL, 3, "./tmp.log");
	if($pid ==-1){
		die('could not fork');
	}else if($pid){
		socket_write($client,'child:'.$child.PHP_EOL);
	        error_log("child=".$child.PHP_EOL, 3, "./tmp.log");
		socket_close($client);
		$child++;
		if($child >= 3){
			pcntl_wait($status);
			$child--;
		}
	}else{
		$buf = socket_read($client,1024);
		echo $buf;
		if(preg_match('/sleep/i',$buf)){
			sleep(10);
			$html = 'HTTP/1.1 200 OK'.PHP_EOL.'Content-Type: text/html;charset=utf-8'.PHP_EOL.PHP_EOL;
	       		 error_log($html,3, "./tmp.log");
			socket_write($client,$html);
			socket_write($client,"this is server,休克了10秒,模拟很繁忙的样子");
		}else{
			
	       		 error_log("this is server ".PHP_EOL,3, "./tmp.log");
			socket_write($client,"this is server");
		}
		 socket_write($client,"i=".$i.PHP_EOL);
	}
	//socket_close($client);
	//exit;
}
socket_close($socket);
