<?php
$key =ftok(__FILE__,'t');
/**
  获取一个信号量资源
  int $key [, int $max_acquire = 1 [, int $perm = 0666 [, int $auto_release = 1 ]]] 
  $max_acquire:最多可以多少个进程同时获取信号
  $perm:权限 默认 0666
  $auto_release：是否自动释放信号量  
 */
$sem_id = sem_get($key);
#获取信号
sem_acquire($seg_id);
//do somethong 这里一个原子性操作

//释放信号
sem_release($seg_id);
//把次信号从系统中移除
sem_remove($sem_id);

//可能出现的问题
$fp = sem_get(fileinode(__DIR__),100);
sem_acquire($fp);

$fp2 = sem_get(fileinode(__DIR__),1);

sem_acquire($fp2);
