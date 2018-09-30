<?php
interface Db_Adapter
{	
	/**
	  *数据连接
	  *@param $config 数据连接配置
	  *return resource
	  */
	public function connect($config);
	/**
	  *执行数据库查询
	  *@param $query SQL语句
	  *@param $handle 连接对象	  
	  *return resource
	  */
	public function query($query,$handle);
}

class Db_Adapter_Mysql implements Db_Adapter
{
	private $_dbLink; //数据库连接字符标识

	/**
	  *数据连接
	  *@param $config 数据连接配置
	  *@throws Db_Exception
	  *return resource
	  */
	public function connect($config)
	{
		if(@$this->_dbLink = new PDO("pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}", $config['user'], $config['pwd']))//连接到数据
		{
			return $this->_dbLink;
		}
		throw new Db_Exception($error);
	}

	/**
	  *执行数据库查询
	  *@param $query SQL语句
	  *@param $handle 连接对象	  
	  *return resource
	  */
	public function query($query,$handle)
	{
		$stmt = $this->_dbLink($query);  

		if($resource=$stmt->execute())
		{
			return $resource;
		}
	}
}

class Db_Adapter_Sqlite implements Db_Adapter
{
	private $_dbLink; //数据库连接字符标识

	/**
	  *数据连接
	  *@param $config 数据连接配置
	  *@throws Db_Exception
	  *return resource
	  */
	public function connect($config)
	{
		if($this->_dbLink = sqlite_open($config->file,0666,$error)){
			return $this->_dbLink;
		}
		throw new Db_Exception($error);
		
	}
	
	/**
	  *执行数据库查询
	  *@param $query SQL语句
	  *@param $handle 连接对象	  
	  *return resource
	  */
	public function query($query,$handle)
	{
		if($resource = @sqlite_query($query,$handle))
		{
			return $resource;
		}
	}
}