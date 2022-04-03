<?php
namespace Banner\Model;
class Banner{
    public $id;
    public $title;
    public $img;
    public $url;
    public $location;
    public $status;
    public $date;
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->title=(isset($data['title']))? $data['title']:null;
        $this->url=(isset($data['url']))? $data['url']:null;
        $this->location=(isset($data['location']))? $data['location']:null;
        $this->img=(isset($data['img']))? $data['img']:null;
        $this->status=(isset($data['status']))? $data['status']:null;
        $this->date=(isset($data['date']))? $data['date']:null;
        
    }
    public function getdata(){
        $data=array();
        $date = date("Y-m-d ");
        $data['title']=  $this->title;
        $data['url']=  $this->url;
        $data['location']=  $this->location;
        $data['img']=  $this->img;       
        $data['date']=$date;
        return $data;
    }
    public function getstatus(){
        $data=array();
        $data['status']=  $this->status;
        return $data;
    }
}

