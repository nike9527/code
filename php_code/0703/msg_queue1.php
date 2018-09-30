<?php
set_time_limit(0);
$ftok = ftok(__FILE__,'a');
$msg_queue = msg_get_queue($ftok); //获取一个消息队列资源
$pidarr = [];
//产生子进程
sem_get($ftok ,1);
$pid = pcntl_fork();
if($pid)
{
	$arr = range(1,10000);
	foreach($arr as $val)
	{
		$status = msg_send($msg_queue,1,$val);
		usleep(1000);
	}
	$pidarr[] = $pid;
	msg_remove_queue($msg_queue);
}else{
	//子进程收到任务后 fork10个紫禁城来处理任务
	for($i = 0 ;$i<10;$i++)
	{
		$childpid = pcntl_fork();
		sem_acquire ( $sem_id );
		if($childpid)
		{
			$pidarr[] = $childpid;
		}else{
			while(true)
			{
				msg_receive($msg_queue,0,$msg_type,1024,$message);
				if(!$message) exit(0);
				echo $message ."&i=$i".PHP_EOL;
				usleep(100);
			}
		}
	}
}

while(count($pidarr)>0)
{
	foreach($pidarr as $key => $pid)
	{
		$status = pcntl_waitpid($pid,$status);
		 sem_remove ( $sem_id );
		if($status == -1 || $status > 0)
		{
			unset($pidarr[$key]);
		}
	}
	sleep(1);
}
