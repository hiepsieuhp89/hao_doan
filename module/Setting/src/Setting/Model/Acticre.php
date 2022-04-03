<?php
namespace Setting\Model;
class Acticre{
    public $id;
    public $title;
    public $alias;
    public $content;
    public $location;
    public $status;   
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->title=(isset($data['title']))? $data['title']:null;
        $this->alias=(isset($data['alias']))? $data['alias']:null;
        $this->content=(isset($data['content']))? $data['content']:null;
        $this->location=(isset($data['location']))? $data['location']:null;
        $this->status=(isset($data['status']))? $data['status']:null;       
    }    
    
    public function data(){
        $data=array();
        $data['title']=  $this->title;
        $data['alias']=  $this->alias;
        $data['content']=  $this->content;
        $data['location']=  $this->location;
        $data['status']=  $this->status;
        return ($data);
    }
    public function status(){
        $data=array();
        $data['status']=  $this->status;
        return ($data);
    }
      public function location(){
        $data=array();
        $data['location']=  $this->location;
        return ($data);
    }
}

