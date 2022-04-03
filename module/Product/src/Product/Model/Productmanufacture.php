<?php
namespace Product\Model;
class Productmanufacture{
    public $id;
    public $manu_name;
    public $alias;
    public $description;   
    public $status;
    public $img;   
    public $date;
    public $seo_title;
    public $seo_keyword;
    public $seo_description;
   
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->manu_name=(isset($data['manu_name']))? $data['manu_name']:null; 
        $this->alias=(isset($data['alias']))? $data['alias']:null;        
        $this->description=(isset($data['description']))? $data['description']:null;
        $this->status=(isset($data['status']))? $data['status']:null;
        $this->img=(isset($data['img']))? $data['img']:null;        
        $this->date=(isset($data['date']))? $data['date']:null;       
        $this->seo_title=(isset($data['seo_title']))? $data['seo_title']:null;
        $this->seo_keyword=(isset($data['seo_keyword']))? $data['seo_keyword']:null;
        $this->seo_description=(isset($data['seo_description']))? $data['seo_description']:null;     
        
    }
    public function datamanu(){
        $data=array();
        $data['manu_name']=  $this->manu_name; 
        $data['alias']=  $this->alias; 
        $data['description']=  $this->description;
        $data['status']= $this->status;        
        $data['img']=  $this->img;
        $data['date']=  $this->date;        
        $data['seo_title']=  $this->seo_title;
        $data['seo_keyword']=  $this->seo_keyword;
        $data['seo_description']=  $this->seo_description;
        return $data;
    }
    public function status(){
        $data=array();        
        $data['status']= $this->status;              
        return $data;
    }
}

