
<?php
	//worker 是一个具有持久化上下文的线程对象，通常用来在多个线程中使用。
	//worker 对象start后，会直接运行run()方法，执行完毕之后，线程也不会die掉
	//SQLQuery 是任务类
	class SQLQuery extends Thread
	{
		public $worker;
		public $sql;
		public function __construct($sql)
		{
			$this->sql = $sql;
		}

		public function run()
		{
		 	$dbh  = $this->worker->getConnection();
	        $row = $dbh->prepare($this->sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	     	$row->execute();
	        while($member = $row->fetch(PDO::FETCH_ASSOC))
	        {
	            // print_r($member);
	         }
		}
	}
	//worker 执行任务
	class ExampleWorker extends Worker {
	        public static $dbh;
	        public function __construct($name) {
	        }

	        /*
	        * The run method should just prepare the environment for the work that is coming ...
	        */
	        public function run(){
	                self::$dbh = new PDO('mysql:dbname=mix;host=192.168.33.11','root','');
	        }
	        public function getConnection(){
	                return self::$dbh;
	        }
	}

	$worker = new ExampleWorker("My Worker Thread");

	for ($i = 0; $i < 5; ++$i) {
	    $worker->stack(new SQLQuery('select * from stores limit '.$i));  // 将要执行的任务入栈
	}
	
	echo "{$worker->getStacked()} tasks\n"; //获取栈中剩余的任务数量
	$worker->start(); 		 //执行完Worker中的对象后
	$worker->shutdown();     //关闭Worker。  跟队列很像

	/*
	这里会报错 
	Uncaught RuntimeException: the creator of ExampleWorker already started
	没有线程die掉
	while(true)
	{
		sleep(5);
		$worker->start();
		$worker->shutdown();
	}
	*/
	
