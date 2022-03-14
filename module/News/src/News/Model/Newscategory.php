<?php
namespace News\Model;
class Newscategory{
    public $id;
    public $name;
    public $parent;
    public $alias;
    public $description;
    public $status;
    public $cat_featured;
    public $cat_new;
    public $date;
    public $mod;
    public $seo_title;
    public $seo_keyword;
    public $seo_description;
   
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->name=(isset($data['name']))? $data['name']:null;
        $this->parent=(isset($data['parent']))? $data['parent']:null;
        $this->alias=(isset($data['alias']))? $data['alias']:null;
        $this->description=(isset($data['description']))? $data['description']:null;
        $this->status=(isset($data['status']))? $data['status']:null;
        $this->cat_featured=(isset($data['cat_featured']))? $data['cat_featured']:null;
        $this->cat_new=(isset($data['cat_new']))? $data['cat_new']:null;
        $this->date=(isset($data['date']))? $data['date']:null;
        $this->mod=(isset($data['mod']))? $data['mod']:null;
        $this->seo_title=(isset($data['seo_title']))? $data['seo_title']:null;
        $this->seo_keyword=(isset($data['seo_keyword']))? $data['seo_keyword']:null;
        $this->seo_description=(isset($data['seo_description']))? $data['seo_description']:null;     
        
    }
    public function datacat(){
        $data=array();
        $data['name']=  $this->name;
        $data['parent']=  $this->parent;
        $data['alias']=  $this->alias;
        $data['description']=  $this->description;
        $data['status']= $this->status;
        $data['cat_featured']=  $this->cat_featured;
        $data['cat_new']=  $this->cat_new;
        $data['date']=  $this->date;
        $data['mod']= $this->mod;
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

