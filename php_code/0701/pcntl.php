<?php
/**
   共享内存通信
**/
//1、创建共享内存区域
$shm_key = ftok(__FILE__,'t');
$shm_id = shm_attach($shm_key,1024,0655);
const SHARE_KEY = 1;
$childList = [];
//加入信号量
$sem_id  = ftok(__FILE__,'s');
$signal = sem_get($sem_id,2);

//2 开启3个进程 读写该内存区域
for($i = 0 ;$i<= 3; $i++)
{
	$pid = pcntl_fork();
	if($pid == -1)
	{
		exit("fork fail".PHP_EOL);
	}else if($pid == 0){
		//获取信号量
		sem_acquire($signal);

		//子进程从共享内存中读取 写值 +1 写会
		if( shm_has_var($shm_id,SHARE_KEY) )
		{
			//有值 加一
			$count = shm_get_var($shm_id,SHARE_KEY);
			$count ++;
				//模拟业务处理逻辑延迟
			$sec = rand(1,3);
			sleep($sec);
			shm_put_var($shm_id,SHARE_KEY,$count);
		}else{
			//初始化
			$count = 0;
			//模拟业务处理延迟
			$sec = rand(1,3);
			sleep($sec);
			shm_put_var($shm_id,SHARE_KEY,$count);
		}
		echo "child process ".getmypid()." is writeing! now count is ".$count.PHP_EOL;
		sem_release($signal);
		exit("child process ".getmypid()."end!".PHP_EOL);
	}else{
		$childList[$pid] = 1;
	}
}

while(!empty($childList))
{

	$chlidPid = pcntl_wait($status);
	if($chlidPid>0)
	{
		echo $chlidPid."消失".PHP_EOL;
		unset($chlidPid[$chlidPid]);
	}
}

$count = shm_get_var($shm_id,SHARE_KEY);
echo "final count is ".$count.PHP_EOL;

//删除内存共享
shm_remove($shm_id);
shm_remove_var($shm_id,$shm_key);
//关闭内存
shm_detach($shm_id);
