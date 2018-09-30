<?php
$key = ftok(__FILE__,'1');
$shar_key = 1;
$shm_id = shm_attach($key,1024,0666);
if($shm_id === false) 
{
	die("Unable to create the shared memsegment".PHP_EOL);
}
//设置一个值
shm_put_var($shm_id,$shar_key,'test');
//删除一个值
//shm_remove_var($shm_id.$shar_key);
//获取一个值
$value = shm_get_var($shm_id,$shar_key);
var_dump($value);

//检车一个key是否存在
//var_dump(shm_has_var($shm_id,$shar_key);
#从系统终移除
shm_remove($shm_id);
//关闭和内存共享的链接
shm_detach($shm_id);
