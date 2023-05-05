<?php
//the flag in flag.php
class A
{
    public $filename;
    public function __construct()
    {
        $this->filename = "hello.txt";
    }
    public function read()
    {
        $res = "";
        if (isset($this->filename)) {
            $res = file_get_contents($this->filename);
        }
        echo $res;
    }
}
class B
{
    public $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function __wakeup()
    {
        return $this->name->read();
    }
}
if (isset($_GET['hix'])) {
    @unserialize($_GET['hix']);
} else {
    highlight_file(__FILE__);
}
