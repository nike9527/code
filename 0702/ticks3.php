<?php
function memoryUsage()
{
	printf("%s:%s".PHP_EOL,date("H:i:s",time()),memory_get_usage());
	//var_dump(debug_backtrace());
	//var_dump(__FUNCTION__);
	//debug_print_backtrace();
}
register_tick_function("memoryUsage");
declare(ticks=1)
{
	$shm_key = ftok(__FILE__,'s');
	$shm_id = shmop_open($shm_key,'c',0644,100);
}

printf("Size of Shared Memory is: %s".PHP_EOL,shmop_size($shm_id));

$shm_text = shmop_read($shm_id,0,100);
eval($shm_text);
if(!empty($share_array))
{
	//var_dump($share_array);
	$share_array['id'] += 1;
}else{
	$share_array = array('id' => 1);
}
 $out_put_str = '$share_array = '.var_export($share_array,true).";";
 $out_put_str = str_pad($out_put_str,100," ",STR_PAD_RIGHT);
 shmop_write($shm_id,$out_put_str,0);
?>
