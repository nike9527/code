<?php
$base = event_base_new();
$event = event_new();

event_set($event, 0, EV_TIMEOUT, function() {
		    echo "function called";
			});
event_base_set($event, $base);

event_add($event, 5000000);
event_base_loop($base);
?>
