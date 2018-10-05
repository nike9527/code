<?php
$pids = array();
$child_pid = pcntl_fork();

if ($child_pid == -1)
{
    throw new Exception( __METHOD__ . "|" . __LINE__ .
            ": fork() error");
}
else if ($child_pid)
{
    //parent 
    exit(0);
}
else
{
    //child
    for($i=0;$i<3;$i++)
    {
        $child_pid = pcntl_fork();
        if($child_pid)
        {
            //parent
            $pids[] = $child_pid;
            sleep(5);
            //print_r($pids);echo "\n";
        }else{
            //child
            break;
        }
    }
}

while(1)
{
     //your code
	echo "PID:".getmypid()."=====".microtime().PHP_EOL;
    sleep(1);
}    
