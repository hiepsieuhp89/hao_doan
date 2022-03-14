<?php
namespace Setting\Model;
class Setting{
    public $id;
    public $logo;
    public $favicon;
    public $website_url;
    public $email;
    public $map;
    public $phone1;
    public $phone2;
    public $hotline;
    public $address;   
    public $about;
    public $seo_title;
    public $seo_keyword;
    public $seo_description;
    public $footer;
    public $facebook;
    public $twiter;
    public $google;
    public $printer;
    public $fanpage;
    public $email_admin;
    public $email_customer;
    public $email_system;
    public $pass_system;
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->logo=(isset($data['logo']))? $data['logo']:null;
        $this->favicon=(isset($data['favicon']))? $data['favicon']:null;
        $this->website_url=(isset($data['website_url']))? $data['website_url']:null;
        $this->email=(isset($data['email']))? $data['email']:null;
        $this->map=(isset($data['map']))? $data['map']:null;
        $this->phone1=(isset($data['phone1']))? $data['phone1']:null;
        $this->phone2=(isset($data['phone2']))? $data['phone2']:null;
        $this->hotline=(isset($data['hotline']))? $data['hotline']:null;
        $this->address=(isset($data['address']))? $data['address']:null;       
        $this->about=(isset($data['about']))? $data['about']:null;
        $this->seo_title=(isset($data['seo_title']))? $data['seo_title']:null;
        $this->seo_keyword=(isset($data['seo_keyword']))? $data['seo_keyword']:null;
        $this->seo_description=(isset($data['seo_description']))? $data['seo_description']:null;
        $this->footer=(isset($data['footer']))? $data['footer']:null;
        $this->facebook=(isset($data['facebook']))? $data['facebook']:null;
        $this->google=(isset($data['google']))? $data['google']:null;
        $this->twiter=(isset($data['twiter']))? $data['twiter']:null;
        $this->printer=(isset($data['printer']))? $data['printer']:null;
        $this->fanpage=(isset($data['fanpage']))? $data['fanpage']:null;
        $this->email_admin=(isset($data['email_admin']))? $data['email_admin']:null;
        $this->email_customer=(isset($data['email_customer']))? $data['email_customer']:null;
        $this->email_system=(isset($data['email_system']))? $data['email_system']:null;
        $this->pass_system=(isset($data['pass_system']))? $data['pass_system']:null;
    }    
    
    public function datasetting(){
        $data=array();
        $data['logo']=  $this->logo;
        $data['favicon']=  $this->favicon;
        $data['website_url']=  $this->website_url;
        $data['email']=  $this->email;
        $data['phone1']=  $this->phone1;
        $data['phone2']=  $this->phone2;
        $data['hotline']=  $this->hotline;
        $data['address']=  $this->address;
        $data['footer']=  $this->footer;
        return $data;
    }
    public function dataseo(){
        $data=array();
        $data['seo_title']=  $this->seo_title;
        $data['seo_keyword']=  $this->seo_keyword;
        $data['seo_description']=  $this->seo_description;       
        return $data;
    }
     public function dataabout(){
        $data=array();
        $data['about']=  $this->about;
        $data['map']=  $this->map;          
        return $data;
    }
     public function datasociu(){
        $data=array();
        $data['facebook']=  $this->facebook;
        $data['twiter']= $this->twiter;
        $data['google']=  $this->google;
        $data['printer']=  $this->printer;
        $data['fanpage']=  $this->fanpage;
        return $data;
    }
     public function dataemail(){
        $data=array();
        $data['email_admin']=  $this->email_admin;
        $data['email_customer']= $this->email_customer;
        $data['email_system']=  $this->email_system;
        $data['pass_system']=  $this->pass_system;        
        return $data;
    }
}

