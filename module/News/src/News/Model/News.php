<?php
namespace News\Model;
class News{
    public $id;
    public $news_title;
    public $news_alias;
    public $news_descripion;
    public $news_contents;
    public $news_img;
    public $news_status;
    public $news_featured;
    public $news_catid;   
    public $seo_title;
    public $seo_keyword;
    public $seo_description;
    public $news_date;
    public $news_mod;
    public $id_user;


    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->news_title=(isset($data['news_title']))? $data['news_title']:null;
        $this->news_alias=(isset($data['news_alias']))? $data['news_alias']:null;
        $this->news_descripion=(isset($data['news_descripion']))? $data['news_descripion']:null;
        $this->news_contents=(isset($data['news_contents']))? $data['news_contents']:null;
        $this->news_status=(isset($data['news_status']))? $data['news_status']:null;
        $this->news_img=(isset($data['news_img']))? $data['news_img']:null;
        $this->news_featured=(isset($data['news_featured']))? $data['news_featured']:null;
        $this->news_catid=(isset($data['news_catid']))? $data['news_catid']:null;
        $this->seo_title=(isset($data['seo_title']))? $data['seo_title']:null;        
        $this->seo_keyword=(isset($data['seo_keyword']))? $data['seo_keyword']:null;
        $this->seo_description=(isset($data['seo_description']))? $data['seo_description']:null;   
        $this->news_date=(isset($data['news_date']))? $data['news_date']:null;
        $this->news_mod=(isset($data['news_mod']))? $data['news_mod']:null;  
        $this->id_user=(isset($data['id_user']))? $data['id_user']:null;  
        
    }
    public function datanews(){
        $data=array();
        $data['news_title']=  $this->news_title;
        $data['news_alias']=  $this->news_alias;
        $data['news_descripion']=  $this->news_descripion;
        $data['news_contents']=  $this->news_contents;
        $data['news_img']= $this->news_img;
        $data['news_status']=  $this->news_status;
        //$data['news_featured']=  $this->news_featured;
        $data['news_catid']=  $this->news_catid;     
        $data['seo_title']=  $this->seo_title;
        $data['seo_keyword']=  $this->seo_keyword;
        $data['seo_description']=  $this->seo_description;
        $data['news_date']=  $this->news_date;
        $data['news_mod']=  $this->news_mod;
        $data['id_user']=  $this->id_user;
        return $data;
    }
    public function status(){
        $data=array();       
        $data['news_status']= $this->news_status;       
        return $data;
    }
}

