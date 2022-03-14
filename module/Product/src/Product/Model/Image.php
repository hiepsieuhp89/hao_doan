<?php
namespace Product\Model;
class Image{
    public $id;
    public $id_product;
    public $img;
    public $medium;
    public $thumbnail;      
    public $status;      
    public $date;  
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->id_product=(isset($data['id_product']))? $data['id_product']:null;
        $this->img=(isset($data['img']))? $data['img']:null;
        $this->medium=(isset($data['medium']))? $data['medium']:null;
        $this->thumbnail=(isset($data['thumbnail']))? $data['thumbnail']:null;        
        $this->status=(isset($data['status']))? $data['status']:null;        
        $this->date=(isset($data['date']))? $data['date']:null;
       
        
    }
    public function dataimg(){
        $date=  date("Y-m-d");
        $data=array();
        $data['id_product']=  $this->id_product;
        $data['img']=  $this->img;
        $data['medium']=  $this->medium;
        $data['thumbnail']=  $this->thumbnail;
        $data['status']=  $this->status;        
        $data['date']=  $date;        
        return $data;
    }
    public function status(){
        $data=array();        
        $data['status']= $this->status;               
        return $data;
    }
}

