<?php

function fun1()
{
	sleep(5);
	echo __FUNCTION__.PHP_EOL;
}

function fun2()
{
	sleep(2);
	echo __FUNCTION__.PHP_EOL;
}


class HelloWorld extends Thread
{
	public $world;

	public function __construct($word)
	{
		$this->world = $word;
	}

	public function run()
	{
		$t1 = time();
		sleep(5);
		$t2 = time();
		echo $t2-$t1;
		print_r(sprintf("Hello %s\n",$this->world));
	}
}


// $mythread = new HelloWorld("world");

// if($mythread->start())
// {	
// 	printf("Thread #%lu says: %s\n",$mythread->getThreadId(),$mythread->join());
// }

$mythread_1 = new HelloWorld("hello");
if($mythread_1->start())
{	
	printf("Thread #%lu says: %s\n",$mythread_1->getThreadId(),1);
 }
 $mythread_1 = new HelloWorld("hello");
if($mythread_1->start())
{	
	printf("Thread #%lu says: %s\n",$mythread_1->getThreadId(),1);
 }