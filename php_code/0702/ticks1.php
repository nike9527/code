<?php
function getStatus($arg)
{
	print_r connection_status();
	echo "-";
	debug_print_backtrace();
}

register_tick_function("getStatus",true);
declare(ticks=1)
{
	for($i = 0 ;$i<999;$i++)
	{
		echo "hello ";
	}
}
unregister_tick_function("getStatus");
