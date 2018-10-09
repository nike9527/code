<?php
class taskWork extends Thread
{
	public $url = '';
	public $name = '';
	public $thread_id = '';
	public $is_runing = true;
	public function __construct($name)
	{
		$this->name = $name;
	}
	public function run()
	{
		while($this->is_runing)
		{
			if(!empty($this->url))
			{
				echo "线程:[{$this->name}］正在处理 URL:[{$this->url}]\r\n";
				$t1 = microtime(true);
				$httpcode = $this->httpcode($this->url);
				$t2 = microtime(true);
				$t = $t2-$t1;
				if($httpcode == 200)
				{
					echo "URL:[{$this->url}] 处理结果 正常 请求时间{$t}\r\n";
				}else{
					echo "URL:[{$this->url}] 处理结果 异常 请求时间{$t}\r\n";
				}
				$this->url = '';
			}else{
				echo "线程:[{$this->name}］等待任务....\r\n";
			}
			sleep(1);
		}
	}
	public function httpcode($url){

		  $ch = curl_init();
		  $timeout = 3;
		  curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		  curl_setopt($ch, CURLOPT_HEADER, 1);
		  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		  curl_setopt($ch,CURLOPT_URL,$url);
		  curl_exec($ch);
		  $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		  curl_close($ch);
		  return $httpcode;
	}
}

$urls=[
	'https://www.1.com',
	'https://www.2.com',
	'https://www.3.com',
	'https://www.baidu.com',
	'https://www.163.com',
	'https://www.qq.com',
	'https://www.www.sina.com.cn',
	'https://www.51cto.com',
	'https://www.9.com',
	'https://www.0.com',
];

$threads[] = new taskWork('thread_1');
$threads[] = new taskWork('thread_2');
$threads[] = new taskWork('thread_3');

foreach($threads as $thread)
{
	$thread->start();
}

for($i = 1; $i<10; $i++)
{
  while (true) {
    foreach ($threads as $worker) {
      if ($worker->url=='') {
        $worker->url = array_pop($urls);
        echo "线程:[{$worker->name}]空闲,放入参数{$worker->url}\r\n";
        break 2;
      }
    }
    sleep(1);
  }
}
echo "所有线程派发完毕,等待执行完成.\r\n";

while (count($threads)) {
  foreach ($threads as $key => $thread) {
    if ($thread->url == '') {
      echo "[{$thread->name}]线程运行完成,空闲 退出.\r\n";
      $thread->is_runing = false;
      unset($threads[$key]);
    }
  }
  echo "等待中其他线程完成...\r\n";
  sleep(1);
}

