<?php
class Daemon
{
	const DLOG_TO_CONSOLE = 1;
	const DLOG_NOTICE = 2;
	const DLOG_WARNING =4;
	const DLOG_ERROR = 8;
	const DLOG_CRITICAL = 16;
	const DAPC_PATH = '/tmp/daemon_apc_keys';

	/**
	  *	User ID
	  * @var int
	  */
	public $userID = 65534;  //nobody

	/**
	  * Group ID 
	  * @var int
	  */
	public $groupID = 65533; //nobody

	/**
	  * 设置守护进程失败终止进程
	  * @var bool 
	  * @since 1.0.3
	  */
	public $requireSetIdentity = false;


	/**
	  * 设置PID的文件路径
	  * @var string 
	  * @since 1.0.1
	  */
	public $pidFileLocation = '/tmp/daemon.pid';

	/**
	  * processLocation
	  * 进程信息记录目录
	  * @var string
	  */
	public $processLocation = '';

	/**
	  * processHeartLocation
	  * 进程心跳包文件
	  * @var string
	  */
	public $processHeartLocation = '';

	/**
	  * 设置工作目录
	  * @var string
	  *	@since 1.0
	  */		
	 public $homePath = "/";


	 /**
	   * 当前进程的ID
	   * @var int
	   * @since 1.0
	   */
	 protected $_pid = 0;


	 /**
	   * 当前进程是否子进程
	   * @var boolean	
	   * @var since 1.0
	   */
	 protected $_isChildren = false;

	 /**
	   * 是否在守护进程运行
	   * @var boolean
	   * @since 1.0
	   */
	 protected $_isRunning = false;

	 public function __construct()
	 {
	 	error_reporting(0);
	 	set_time_limit(0);
	 	ob_implicit_flush();
	 }


	 /**
	   * 启动进程
	   * @return bool
	   */
	 public function main()
	 {
	 	$this->_logMessage('Starting daemon');
	 	if(!$this->_daemonize())
	 	{
	 		$this->_logMessage("Could not start daemon",self::DLOG_ERROR);
	 		return false;
	 	}
	 	$this->_logMessage("Running....");
	 	$this->_isRunning = true;
	 	while($this->_isRunning)
	 	{
	 		$this->_doTask();
	 	}
	 	return true;
	 }

	 /**
	   * 停止进程
	   *
	   * @return void
	   */
	 public function stop()
	 {
	 	$this->_logMessage("Stoping daemon");
	 	$this->_isRunning = false;
	 }

	 /**
	   * 任务
	   * @return void
	   */
	 public function _doTask()
	 {
	 	echo 1;
	 }

	/**
	  * _logMessage
	  * 记录日志
	  *
	  * @param string 消息
	  * @param integer 级别
	  * @return void
	  */
	protected function _logMessage($msg,$level = self::DLOG_NOTICE)
	{

	}

	/**
      * Daemonize
      *
      * Several rules or characteristics that most daemons possess:
      * 1) Check is daemon already running
      * 2) Fork child process
      * 3) Sets identity
      * 4) Make current process a session laeder
      * 5) Write process ID to file
      * 6) Change home path
      * 7) umask(0)
      *
      * @access private
      * @since 1.0
      * @return void
      */

	private function _daemonize()
	{
		ob_end_flush();
		//守护进程是否运行
		if($this->_isDaemonRunning())
		{
			return false;
		}
		if(!$this->_fork())
		{
			return false;
		}
		if(!$this->_setIdemtity() && $this->requireSetIdentity)
		{
			return false;
		}
		if(!posix_setsid())
		{
			$this->_logMessage("Could not make the current process a session leader",self::DLOG_ERROR);
		}
		if($fp = fopen($this->pidFileLocation, 'w'))
		{
			$this->_logMessage("Could not write to Pid file",self::DLOG_ERROR);
		}else{
			fputs($fp,$this->_pid);
			fclose($fp);
		}
		$this->writeProcess();
		chdir($this->homePath);
		umask(0);
		declare(ticks = 1);
		pcntl_signal(SIGCHLD, array(&$this,'sigHandler'));
		pcntl_signal(SIGTERM, array(&$this,'sigHandler'));
		pcntl_signal(SIGUSR1, array(&$this,'sigHandler'));
		pcntl_signal(SIGUSR2, array(&$this,'sigHandler'));

	}

	private function _isDaemonRunning()
	{
		$oldPid = file_get_contents($this->pidFileLocation);
		echo $this->pidFileLocation;
		if($oldPid !== false && posix_kill(trim($oldPid),0))
		{
			$this->_logMessage("daemon already running with PID:".$oldPid, (self::DLOG_TO_CONSOLE | self::DLOG_ERROR));
			return true;
		}else{
			return false;
		}
	}

	private function __fork()
	{
		$this->_logMessage('Foking...');
		$pid = pcntl_fork();
		if($pid == -1)
		{
			$this->_logMessage('Could not fork', self::DLOG_ERROR);
			return false;
		}elseif($pid){
			$this->_logMessage('Killing parent');
  			exit();
		}else{
			$this->_isChildren = true;
			$this->_pid = posix_getpid();
			return true;
		}
	}

	private function _setIdemtity()
	{
		if (!posix_setgid($this->groupID) || !posix_setuid($this->userID))
		{
			$this->_logMessage('Could not set identity', self::DLOG_WARNING);
			return false;
		}else{
			return true;
		}
	}

	public function sigHandler($sigNo)
	{
		switch ($sigNo) {
			case 'SIGTERM':
				$this->_logMessage('Shutdown signal');
				exit();
				break;
			case 'SIGCHLD':
				$this->_logMessage('Halt signal');
				while (pcntl_waitpid(-1, $status, WNOHANG) > 0);
				break;
			case 'SIGUSR1':
				$this->_logMessage('User-defined signal 1');
				$this->_sigHandlerUser1();
				break;
			case 'SIGUSR2':
				$this->_logMessage('User-defined signal 2');
				$this->_sigHandlerUser2();
				break;												
		}
	}

	protected function _sigHandlerUser1()
	{
		apc_clear_cache('user');
	}

	protected function _sigHandlerUser2()
	{
		$this->_initProcessLocation();
		file_put_contents($this->processHeartLocation, time());
		return true;
	}

	public function releaseDaemon()
	{
		if($this->_isChildren && is_file(($this->pidFileLocation)))
		{
			$this->_logMessage("Releasing daemon");
			unlink($this->pidFileLocation);
		}
	}

	protected function _initProcessLocation()
	{
		$this->processLocation = ROOT_PATH.'/app/data/proc';
		$this->processHeartLocation = $this->processLocation . '/' . $this->_pid . '/heart';
	}

	public function writeProcess()
	{
		$this->_initProcessLocation();
		$command = trim(implode(' ', $_SERVER['argv']));
		$processDir = $this->processLocation."/".$this->pid;
		$processCmdFile = $processDir . '/cmd';
		$processPwdFile = $processDir . '/pwd';

		if(!is_dir($this->processLocation))
		{
			mkdir($this->processLocation,0777);
			chmod($processDir, 0777);
		}
		$pDirObject = dir($this->processLocation);
		while($pDirObject && (($pid = $pDirObject->read())!=false))
		{
			if($pid == "." || $pid == ".." || intval($pid) != $pid)
			{
				continue;
			}
			$pDir = $this->processLocation . '/' . $pid;
			$pCmdFile = $pDir . '/cmd';
			$pPwdFile = $pDir . '/pwd';
			$pHeartFile = $pDir . '/heart';
			if (is_file($pCmdFile) && trim(file_get_contents($pCmdFile)) == $command) {
				unlink($pCmdFile);
				unlink($pPwdFile);
				unlink($pHeartFile);
				usleep(1000);
				rmdir($pDir);
			}
			if (!is_dir($processDir)) {
				mkdir($processDir, 0777);
				chmod($processDir, 0777);
			}
			file_put_contents($processCmdFile, $command);
			file_put_contents($processPwdFile, $_SERVER['PWD']);
			usleep(1000);
			return true;
		}
	}
}

$d = new Daemon();
$d->main();