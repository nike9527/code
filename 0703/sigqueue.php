<?php
/**
  * 使用共享内存和信号量实现
  * 
  * 支持多进程, 支持各种数据类型的存储
  * 注: 完成入队或出队操作,尽快使用unset(), 以释放临界区
  *
 **/
Class ShmQueue
{
	private $maxQsize = 0;       //队列最大长度
	private $front = 0;          //队头指针
	private $rear = 0;           //队尾指针
	private $blockSize = 256;    //块的大小（byte）
	private $memSize = 25600;    //最大共享内存
	private $shmId = 0;          
	private $filePtr = './shmq.ptr';
	private $semId = 0;

	public function __construct()
	{
		$shmKey = ftok(__FILE__,'t');
		$this->shmId = shmop_open($shmKey,'c',0644,$this->memSize);
		$this->maxQSize = $this->memSize / $this->blockSize;
		//申请一个信号
		$this->semId = sem_get($shmKey,1);
		sem_acquire($this->semId);
		$this->init();
	}
	private function init()
	{
		if(file_exists($this->filePtr))
		{
			$contents = file_get_contents($this->filePtr);
			$data = explode("|",$contents);
			if(isset($data[0]) && isset($data[1]))
			{
				/$this->front = (int)$data[0];
				$this->rear = (int)$data[1];
			}
		}
	}
	public function getLength()
	{
		return (($this->rear - $this->front + $this->memSize) % ($this->memSize))/$this->blockSize;
	}

	public function enQueue($value)
	{
		//队满
		if($this->ptrInc($this->rear) == $this->front){
			return false;
		}
		$data = $this->encode($value);
		shmop_write($this->shmId,$data,$this->rear);
		$this->rear = $this->ptrInc($this->rear);
		return true;
	}

	public function deQueue()
	{   //队空
		if($this->front == $this->rear)
		{
			return false;
		}
		$value = shmop_read($this->shmId,$this->front,$this->blockSize-1);
		$this->front = $this->ptrInc($this->front);
		return $this->decode($value);
	}
	private function ptrInc($ptr)
	{
		return ($ptr + $this->blockSize) % ($this->memSize);
	}

	private function encode($value)
	{
		$data = serialize($value)."__eof";
		echo PHP_EOL;
		echo strlen($data);
		echo PHP_EOL;
		echo $this->blockSize -1;
		echo PHP_EOL;
		if(strlen($data)>$this->blockSize-1){
			throw new Exception(strlen($data)."is overload block size!");
		}
		return $data;
	}

	private function decode($value)
	{
		$data = $this->front ."|" .$this->rear;
		file_put_contents($this->filePtr,$data);
		sem_release($this->semId);
	}
}


$shmq = new ShmQueue(); 
for ($i=0; $i < 10; $i++) { 
	$data = 'test data'.$i; 
	$shmq->enQueue($data);
}
$data = 'test data'; 
$shmq->enQueue($data);
$data = $shmq->deQueue();
var_dump($data);

