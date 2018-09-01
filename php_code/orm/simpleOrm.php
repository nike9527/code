<?php
abstract class ActiceRecord{
	protected static $table="a";
	protected $fieldvalues;
	static function findById($id)
	{
		$query ="select * from ".static::$table." where id={$id}";
		return self::createDomain($query);
	}
	function __get($name)
	{
		return $this->fieldvalues[$name];
	}
	static function __callStatic($methond,$args)
	{
		$filed = preg_replace('/^findBy(\w*)$/', '${1}',$methond);
		$query = "select * from ".static::table."where $filed = '{$args[0]}'";
		return self::createDomain($query);
	}
	private static function createDomain($query)
	{
		$klass = get_called_class();
		$domain= new $klass();
		$domain->fieldvalues = array();
		$domain->select=$query;
		foreach ($klass::$fileds as $filed => $type) {
			$domain->fieldvalues[$filed] = "TODO: set from sql result";
		}
		return $domain;
	}
}

class Customer extends ActiceRecord
{
	protected static $table = 'customer';
	protected static $fileds = [
		'id'=>'int',
		'email'=>'varchar',
		'lastname'=>'varchar'
	];
}


class Sales extends ActiceRecord
{
	
	protected static $table = 'sales';
	protected static $fileds = [
		'id'=>'int',
		'item'=>'varchar',
		'qty'=>'int'
	];
}

echo Customer::findById(123)->select.PHP_EOL;
echo Customer::findById(123)->email.PHP_EOL;;
echo Sales::findById(123)->select.PHP_EOL;;
echo Customer::findById("Denoncourt")->select.PHP_EOL;;