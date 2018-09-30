<?php 
class sqlFactory
{
	public static function factory($type)
	{
		if(include_once("Db_Adapter.php"))
		{
			$classname = 'Db_Adapter_'.$type;
			return new $classname;
		}else{
			throw new Exception("Error Processing Request", 1);
			
		}
	}
}