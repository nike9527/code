<?php
defined("DEAMON_LOCK_FILE") || define("DEAMON_LOCK_FILE","runing/deamon.pid");
if($_SERVER['argc'] >= 2 && $_SERVER['argv'][1] == 'kill')
{
	$fh = fopen(realpath(__DIR__).'/'.DEAMON_LOCK_FILE,'r');
	$pid = fread($fh,8);
	if($pid)
		posix_kill($pid,SIGTERM);
	exit;
}
global $DEAMON_LOCK_HANDLER;
function daemonize($signalHandler = false)
{
	global $DEAMON_LOCK_HANDLER; 
	if(!deamon_file_lock())
	{
		printf("deamon is already runing...\n");
		exit();
	}
	umask(0);
	$pid = pcntl_fork();
	if($pid < 0)
	{
		printf("Cant't fork\n");
		exit;
	}else if($pid){
		printf("partner process exit\n");
		exit;
	}
	$sid = posix_setsid();
	if($pid < 0){
		printf("Can't set session leader\n");
		exit;
	}
	deamon_bind_signals($signalHandler);
	$pid = pcntl_fork();
	if($pid < 0 || $pid)
	{
		exit;
	}
	ftruncate($DEAMON_LOCK_HANDLER,0);
	fwrite($DEAMON_LOCK_HANDLER,posix_getpid());
	chdir('/');
	fclose(STDIN);
	fclose(STDOUT);
	fclose(STDERR);
}
function deamon_bind_signals($signalHandler = false)
{
	$signalHandler = !$signalHandler ? "deamon_signal_handler" : $signalHandler;
	pcntl_signal(SIGTERM,$signalHandler);
	pcntl_signal(SIGHUP,$signalHandler);
	pcntl_signal(SIGUSR1,$signalHandler);
	pcntl_signal(SIGINT,$signalHandler);
}
function deamon_file_lock()
{
	global $DEAMON_LOCK_HANDLER; 
    $DEAMON_LOCK_HANDLER = fopen(realpath(__DIR__).'/'.DEAMON_LOCK_FILE,'c');
	if(!$DEAMON_LOCK_HANDLER)
	{
		printf("Can't open lock file\n");
		die();
	}
	if(!flock($DEAMON_LOCK_HANDLER,LOCK_EX|LOCK_NB))
	{
		return false;
	}
	return true;
	
}
function deamon_signal_handler($signo)
{
	error_log("ok",3,"/tmp/a.log");
	switch($signo){
		case SIGTERM:
		case SIGHUP:
		case SIGUSR1:
			break;
	}
}
function sighandler($sig)
{
	error_log("ok",3,"/tmp/a.log");
	if($sig == SIGTERM)
	{
		global $DEAMON_LOCK_HANDLER;
		fclose($DEAMON_LOCK_HANDLER);
		exit;
	}
}
daemonize("sighandler");
while(1)
{
	pcntl_signal_dispatch();
//	error_log("ok",3,"/tmp/a.log");
	sleep(1);
}
?>
