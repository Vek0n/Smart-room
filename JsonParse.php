<?php
abstract class JsonParse{
    private $obj;
    public function __construct($address){
        $json = file_get_contents($address);
        $this->$obj = json_decode($json,true);
    }
    public function getData($a, $b= NULL, $c=NULL, $d=NULL){
        if(!is_null($d)){
            return $this->$obj[$a][$b][$c][$d];
        }else if (!is_null($c)){
            return $this->$obj[$a][$b][$c];
        }else if (!is_null($b)){
            return $this->$obj[$a][$b];
        }else {
            return $this->$obj[$a];
        }
    }
}
?>