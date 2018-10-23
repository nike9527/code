<?php
/*
declare(ticks = 1);
pcntl_signal(SIGHUP, function(){
    // 这地方处理信号的方式我们只是简单的写入一句日志到文件中
    file_put_contents('logs.txt', 'pid : ' . posix_getpid() . ' receive SIGHUP 信号' . PHP_EOL);
});
while(true){
    echo time().PHP_EOL;
    sleep(3);
}
*/
$pid = pcntl_fork();
if($pid == -1)
{
	throw New Exception("fork子进程失败");
}elseif($pid > 0){
	exit(0);//退出父进程，使孤儿子进程被一号进程收养，脱离终端
}
//最重要的一步，让该进程脱离之前的会话，终端，进程组的控制
posix_setsid();
/*
*修改当前进程的工作目录，
*由于子进程会继承父进程的工作目录
*修改工作目录以释放对父进程工作目录的占用。
*/
chdir('/');
/*
 * 通过上一步，我们创建了一个新的会话组长，进程组长，
 * 且脱离了终端，但是会话组长可以申请重新打开一个终端，为了避免
 * 这种情况，我们再次创建一个子进程，并退出当前进程，这样运行的进程就不再是会话组长。
 */
$pid = pcntl_fork();

if($pid == -1)
{
	throw New Exception("fork子进程失败");
}elseif($pid > 0){
	exit(0);//退出父进程，使孤儿子进程被一号进程收养，脱离终端
}
fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);
sleep(10);
while(true)
{
	echo 1;
	file_put_contents('log.txt', time().PHP_EOL, FILE_APPEND);
	sleep(5);
}