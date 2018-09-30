<?php
$msg_key = ftok(__FILE__,'m');
$msg_id = msg_get_queue($msg_key,0666);
function fetchMessage($msg_id)
{
	if(!is_resource($msg_id))
	{
		print_r("Mesage Queue is not Ready");
		echo PHP_EOL;
		die;
	}
	if(msg_receive($msg_id,0,$meg_type,1024,$mesg,false,MSG_IPC_NOWAIT))
	{
		print_r("Process got a new incoming MSG:$mesg");
	}
}
register_tick_function("fetchMessage",$msg_id);
declare(ticks = 2);

	$i = 0;
	while(++$i<100){
		if($i%5 == 0)
			msg_send($msg_id,1,"Hi: Now Index is :$i");
			echo PHP_EOL;
	}

