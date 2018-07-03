<?php
    $pid = pcntl_fork();
    if($pid) {
        //创建成功，在父进程中执行
        echo "run in parent process PID = $pid".PHP_EOL;//pcntl_wait($status);
    } else if($pid == -1) {
        //创建失败，在父进程中处理
        echo "Couldn't create child process PID = $pid.".PHP_EOL;
    } else {
        //创建成功，在子进程中执行
        //再次创建子进程，即孙进程
        $pid = pcntl_fork();
        if($pid == 0) {
            //在孙进程中执行
            if(-1 == posix_setsid())
            {
                // 出错退出
                exit("Setsid fail".PHP_EOL);
            }
            echo "run in grandchild process  PID = $pid.".PHP_EOL;
        } else if($pid == -1) {
            echo "Couldn’t create child process  PID = $pid.".PHP_EOL;
        } else {
            //在子进程中处理
            echo "run in child process  PID = $pid.".PHP_EOL;//posix_kill(posix_getpid(), SIGUSR1);
            exit;
        }
    }
?>