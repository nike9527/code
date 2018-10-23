<?php
class node{
     public $id;
     public $next;
     public function __construct($id=null,$next=null){
         $this->id=$id;
         $this->next=$next;
     }
}
class singleLinkedList
{
    public $header = null;

    public function addLink($node,$index=0)
    {   $i = 0;
        if(is_null($this->header))
        {
            $this->header = $node;
        }else{
            $curr = $this->header;
            while ($curr->next != null) 
            {
                $i ++;
                if($i<=$index)
                {
                    break;
                }
                $curr = $curr->next;
             }
            $node->next = $curr->next;
            $curr->next = $node; 
        }

    }

    public function delLink($index=1)
    {
        $i = 1;
        $curr = $this->header;
        while ($curr->next != null) 
        {
            if($i<=$index)
            {              
                $tmp = $curr->next;
                break;
            }
            $curr = $curr->next;
            $i ++;
         }
         $curr->next=$tmp->next;
    }
}


$lists = new singleLinkedList(); 
$lists->addLink (new node(1));
$lists->addLink (new node(2));
$lists->addLink (new node(3));
$lists->addLink (new node(4));
$lists->addLink (new node(5));
$lists->addLink (new node(6));
$lists->delLink ();
print_r($lists->header);die;