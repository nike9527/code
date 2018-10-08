<?php
function title_1($title)
{
	print_r(__FUNCTION__.$title."\r\n");
}
function title_2($title)
{
	print_r(__FUNCTION__.$title."\r\n");
}
class myThread extends Thread
{
	public $title;
	public function __construct($title){
		$this->title = $title;
	}
	public function run()
	{
//		sleep(2);
		echo time();
		print_r(sprintf("Hello %s\n",$this->title));
	}
}

for($i=0; $i<10;$i++)
{
	$threads[] = new myThread("title_".$i);	
}

foreach($threads  as $val)
{
	$val->start();
//	$val->join();
}
echo "done\r\n";
