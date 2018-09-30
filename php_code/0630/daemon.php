<?php 
class Deamon{
	private $_pidFile;
	private $_jobs = array();
	private $_infoDir;

	public function __construct($dir = '/tmp'){
		$this->_setInfoDir($dir);
		$this->_pidFile = rtrim($this->_infoDir,'/').'/'.__CLASS__.'pid.log';
		$this->_checkPcntl();
	}
	
	private function _demonize()
	{
		if(php_sapi_name() != 'cli')
		{
			die('Should run in CLI');
		}
		
		$pid = pcntl_fork();
		echo "PID = ".$pid;
		if($pid < 0)
		{
			die('Can\'n Fork!');
		}else if($pid > 0 )
		{
			exit();
		}

		if(posix_setsid() === 1)
		{
			die('Could not detach');
		}

		chdir('/');
		umask(0);
		$fp = fopen($this->_pidFile,'w') or die ('Can\'n create pid file');
		fwrite($fp,posix_getPid());
		fclose($fp);

		if(!empty($this->_jobs))
		{
			foreach($this->_jobs as $job)
			{
				if($empty($job['argv']))
				{
					call_user_func($job['function'],$job['argv']);
				}else{
					call_user_func($job['function']);
				}
			}
		}
		return ;
	}
	private function _setInfoDir($dir = null)
	{
		if(is_dir($dir)){
			$this_infoDir = $dir;
		}else
		{
			$this->_infoDir = __DIR__;
		}
	}
	private function _checkPcntl()
	{
		!function_exists('pcntl_signal') && die('Error: Need PHP pcntl extension');
	}
	private function _getPid()
	{
		if(!file_exists($this->_pidFile))
		{
			return 0;
		}
		$pid = intval(file_get_contents($this->_pidFile));
		if(posix_kill($pid,SIG_DFL))
		{
			return $pid;
		}else{
			unlink($this->_pidFile);
			return 0;	
		}
	}
	private function _message($message)
	{
		printf("%s %d %d %s".PHP_EOL,date("Y-m-d H:i:s"),posix_getPid(),posix_getppid(),$messags);
	}
	public function start()
	{
		if($this->_getPid() > 0)
		{
			$this->_message('Runing');
		}else{
			$this->_demonize();
			$this->_message('Start');
		}
	}
	public function stop()
	{
		$pid = $this->_getPid();
		if($pid > 0 )
		{
			posix_kill($this->_pidFile);
			unlink($this->_pidFile);
			echo 'Stoped' .PHP_EOL;			
		}else{
			echo "Not Runing 1" .PHP_EOL;
		}
	
	}
	public function status()
	{
		$pid = $this->_getPid();
		if($pid > 0 )
		{
			$this->_message("is Runing");
		}else{
			echo 'Not Running 2'.PHP_EOL;
		}
	}

	public function addJobs($jobs = array())
	{
		if(!isset($jobs['function']) || empty($jobs['function']))
		{
			$this->_message("Need function params");
		}
		if(!isset($jobs["argv"])||empty($jobs['argv'])){
			$jobs['argv'] = "";
		}
		$this->_jobs[] = $jobs;
	}

	public function run($argv)
	{
		$params = is_array($argv) && count($argv) == 2 ? $argv[1] : null;
		switch($params)
		{
			case 'start':
				$this->start();
				break;
			case 'stop':
				$this->stop();
				break;
			case 'status':
				$this->status();
				break;
			default:
				echo "Argv start|stop|status".PHP_EOL;
				break;
		}	
	}

}
$deamo = new Deamon();
$deamo->addJobs(array('function'=>'test','argv'=>'Go'));
$argv = ['status','start'];
$deamo->run($argv);
function test($param)
{
	$i = 0;
	while(true)
	{
		echo 'Now is '.$param .PHP_EOL;
		$i++;
		sleep(5);
	}

}
