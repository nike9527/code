<?php
class mysql{
	function connect($db)
	{
		echo "连接到数据库{$db[0]}".PHP_EOL;
	}
}

class sqlProxy{
	private $target;
	function __construct($tar)
	{
		$this->target[] = new $tar();
	}

	function __call($name,$args)
	{
		foreach ($this->target as $obj) {
			$r = new ReflectionClass($obj);
			if($method = $r->getMethod($name)){
				if($method->isPublic() && !$method->isAbstract()){
					echo "方法连接前记录".PHP_EOL;
					$method->invoke($obj,$args);
					echo "方法连接后的记录".PHP_EOL;
				}
			}
		}
	}
}

$obj = new sqlProxy("mysql");
$obj->connect("member");