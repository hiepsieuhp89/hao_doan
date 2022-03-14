<?php
namespace Product\Model;
class Chatlieu{
    public $id;
    public $name;
    public $status;  
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->name=(isset($data['name']))? $data['name']:null; 
        $this->status=(isset($data['status']))? $data['status']:null;  
    }
    public function data(){        
        $data=array();
        $data['name']=  $this->name; 
        $data['status']=  $this->status; 
        return $data;
    }
    public function status(){
        $data=array();        
        $data['status']= $this->status;              
        return $data;
    }
   
}

