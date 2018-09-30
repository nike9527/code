<?php
$shm_key = ftok(__FILE__,'t');

/**
  开辟一块共享内存
**/

$shm_id = shmop_open($shm_key,'c', 0655,5);
$size = shmop_write($shm_id,"hello world",0);
echo "write into ($size)";
$data=shmop_read($shm_id,0,5);
var_dump($data);
shmop_delete($shm_id);
shmop_close($shm_id);
