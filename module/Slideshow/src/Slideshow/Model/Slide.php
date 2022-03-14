<?php
namespace Slideshow\Model;
class Slide{
    public $id;
    public $title;
    public $img;
    public $url;
    public $status;
    public $date;
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->title=(isset($data['title']))? $data['title']:null;
        $this->url=(isset($data['url']))? $data['url']:null;
        $this->img=(isset($data['img']))? $data['img']:null;
        $this->status=(isset($data['status']))? $data['status']:null;
        $this->date=(isset($data['date']))? $data['date']:null;
        
    }
    public function getdata(){
        $data=array();
        $date = date("Y-m-d ");
        $data['title']=  $this->title;
        $data['url']=  $this->url;
        $data['img']=  $this->img;
        $data['status']=  $this->status;
        $data['date']=$date;
        return $data;
    }
    public function getstatus(){
        $data=array();
        $data['status']=  $this->status;
        return $data;
    }
}

