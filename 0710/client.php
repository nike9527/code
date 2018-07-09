<?php
$socket_client=stream_socket_client('tcp://127.0.0.1',$errno,$errstr,30);
fwrite($socket_client,"hellow world=>client");
sleep(1);
$return = fread($socket_client,1024);
echo "come from server: $return".PHP_EOL;
sleep(2);
fwrite($socket_client,"send again!");
$return = fread($socket_client,1024);
echo "comme from server: $return ".PHP_EOL;
