<?php
/*
在进行并发操作时,会导致共享数据的完整性的问题,要加入锁,在任意时刻只有一个线程访问该对象
在PHP中定义专门用于线程同步控制的mutex的函数, pthreads v3 中已经将 Mutex 类移除。
简单的计数器程序，有加锁和没有加锁的情况,pthreads v3使用synchronized同步处理
*/
class CounterThread extends Thread {
	public $counter = 0;
	public $handle;
	public $mutex;
	public function __construct($handle,$mutex = null){
		$this->mutex = $mutex;
    }
    public function run() {
    	//加锁
    	if($this->mutex){
    		$locked=Mutex::lock($this->mutex);
    	}
    	$this->handle = fopen("/tmp/counter.txt", "w+");
    	$this->counter = intval(fgets($this->handle));
    	$this->counter++;
        rewind($this->handle);
    	fwrite($this->handle, $this->counter."\r\n");
		printf("Thread #%lu says: %s\n", $this->getThreadId(),$this->counter);
		$this->close();
		if($this->mutex){
			//释放锁
			Mutex::unlock($this->mutex);
		}
    }
    public function close()
    {
    	print_r(fclose( $this->handle));echo PHP_EOL;
    }
}
$handle=fopen("/tmp/counter.txt", "w+");
//没有互斥锁
for ($i=0;$i<10;$i++){
	$threads[$i] = new CounterThread($handle);
	$threads[$i]->start();
	sleep(1);
}

//创建一个互斥锁 
//参数设置true，表示创建互斥量之后，立即加锁，
$mutex = Mutex::create();
for ($i=0;$i<10;$i++){
	$threads[$i] = new CounterThread($handle,$mutex);
	$threads[$i]->start();
	sleep(1);
}
//销毁互斥量
Mutex::destroy($mutex);

/*
Mutex::unlock($mutex);
for ($i=0;$i<50;$i++){
	$threads[$i]->join();
}
Mutex::destroy($mutex);
*/

?>