<?php
namespace Product\Model;
class Productcategory{
    public $id;
    public $name;
    public $parent;
    public $alias;
    public $description;
    public $status;
    public $show_index;  
    public $date;    
    public $seo_title;
    public $seo_keyword;
    public $seo_description;
    public $user;    
    public $phone;
    public $img;
    public $icon;
    public $background;
    public $sort;
    public $sort_menu;


    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->name=(isset($data['name']))? $data['name']:null;
        $this->parent=(isset($data['parent']))? $data['parent']:null;
        $this->alias=(isset($data['alias']))? $data['alias']:null;
        $this->description=(isset($data['description']))? $data['description']:null;
        $this->status=(isset($data['status']))? $data['status']:null;
        $this->show_index=(isset($data['show_index']))? $data['show_index']:null;       
        $this->date=(isset($data['date']))? $data['date']:null;        
        $this->seo_title=(isset($data['seo_title']))? $data['seo_title']:null;
        $this->seo_keyword=(isset($data['seo_keyword']))? $data['seo_keyword']:null;
        $this->seo_description=(isset($data['seo_description']))? $data['seo_description']:null;   
        $this->user=(isset($data['user']))? $data['user']:null;        
        $this->phone=(isset($data['phone']))? $data['phone']:null;
        $this->img=(isset($data['img']))? $data['img']:null;
        $this->icon=(isset($data['icon']))? $data['icon']:null;   
        $this->background=(isset($data['background']))? $data['background']:null;   
        $this->sort=(isset($data['sort']))? $data['sort']:null;   
        $this->sort_menu=(isset($data['sort_menu']))? $data['sort_menu']:null;   
        
    }
    public function datacat(){
        $data=array();
        $data['name']=  $this->name;
        $data['parent']=  $this->parent;
        $data['alias']=  $this->alias;
        $data['description']=  $this->description;
        $data['status']= $this->status;
        $data['show_index']=  $this->show_index;       
        $data['date']=  $this->date;       
        $data['seo_title']=  $this->seo_title;
        $data['seo_keyword']=  $this->seo_keyword;
        $data['seo_description']=  $this->seo_description;
        $data['user']=  $this->user;       
        $data['phone']=  $this->phone;
        $data['img']=  $this->img;
        $data['icon']=  $this->icon;
        $data['background']=  $this->background;
        return $data;
    }
    public function status(){
        $data=array();        
        $data['status']= $this->status;        
        return $data;
    }
    public function dataparent(){
        $data=array();        
        $data['parent']= $this->parent;        
        return $data;
    }
     public function datashowindex(){
        $data=array();        
        $data['show_index']= $this->show_index;        
        return $data;
    }
    public function datasort(){
        $data =array();
        $data['sort']=$this->sort;
        return $data;
    }
    public function datasort_menu(){
        $data =array();
        $data['sort_menu']=$this->sort_menu;
        return $data;
    }
}

