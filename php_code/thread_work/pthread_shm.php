<?php
$tmp = tempnam(__FILE__, 'PHP');
$key = ftok($tmp, 'a');
$shmid = shm_attach($key);
$counter = 0;
shm_put_var( $shmid, 1, $counter );
class CounterThread extends Thread {
	public $shmid;
	public $is_runing = true;
	public function __construct($shmid){
        $this->shmid = $shmid;
    }
    public function run() {
    	while($this->is_runing)
    	{
    		$counter = shm_get_var( $this->shmid, 1 );
			$counter++;
			shm_put_var( $this->shmid, 1, $counter );
			printf("Thread #%lu says: %s\n", $this->getThreadId(),$counter);
				sleep(1);
    	}
		
    }
}
for ($i=0;$i<10;$i++){
	$threads[] = new CounterThread($shmid);
}
for ($i=0;$i<10;$i++){
	$threads[$i]->start();
}
for ($i=0;$i<10;$i++){
	$threads[$i]->join();
}

shm_remove( $shmid );
shm_detach( $shmid );