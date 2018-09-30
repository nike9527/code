<?php
class strClass
{
    protected $str = '';
    public function __construct($str=null)
    {
        $this->str = $str;
    }
    public function __call($name,$arguments)
    {
        $this->createFunction($name,$arguments);
        return $this;
    }


     public function __get($str)
    {
        return $str;
    }

    private function createFunction($name,$arguments)
    {
        if(function_exists($name)){
           // $this->str = $name($this->str);
             $this->str = call_user_func($name, $this->str);
        }
    }

    public function __toString()
    {
        return "$this->str";
    }
}
$str = new strClass(" aaa ");
echo $str->trim()->strlen()->a();