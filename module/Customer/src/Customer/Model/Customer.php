<?php

namespace Customer\Model;

use Zend\Session\Container;
class Customer {

    public $id;
    public $fullname;    
    public $email;
    public $address;
    public $company;
    public $phone;
    public $city;   
    public $password;    
    public $yahoo;    
    public $skype;
    public $facebook;
    public $status;
    public $date;
    public $cus_mod;   

    public function exchangeArray($data) {
        $this->ID = (isset($data['ID'])) ? $data['ID'] : null;
        $this->fullname = (isset($data['fullname'])) ? $data['fullname'] : null;        
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->address = (isset($data['address'])) ? $data['address'] : null;
        $this->company = (isset($data['company'])) ? $data['company'] : null;
        $this->phone = (isset($data['phone'])) ? $data['phone'] : null;
        $this->city = (isset($data['city'])) ? $data['city'] : null;        
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->yahoo = (isset($data['yahoo'])) ? $data['yahoo'] : null;      
        $this->skype = (isset($data['skype'])) ? $data['skype'] : null;
        $this->facebook = (isset($data['facebook'])) ? $data['facebook'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;       
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->cus_mod = (isset($data['cus_mod'])) ? $data['cus_mod'] : null;       
    }

    public function getdata() {
        $data = array();         
        $data['fullname'] = $this->fullname;
        $data['email'] = $this->email;
        $data['address'] = $this->address;
        $data['company'] = $this->company;
        $data['phone'] = $this->phone;       
        $data['password'] = $this->password;
        $data['yahoo'] = $this->yahoo;
        $data['skype'] = $this->skype;
        $data['facebook'] = $this->facebook;       
        $data['date'] = $this->date;
        $data['cus_mod'] = $this->cus_mod;        
        return $data;
    }
   
    public function datapass(){
        $data=array();
        $data['password'] = $this->password;
        return $data;
    }
    
     public function status(){
        $data=array();       
        $data['status']= $this->status;       
        return $data;
    }
     public function checklogin(){
         $session_user = new Container('userlogin');
         if($session_user->username == null){
             $url=WEB_PATH.'/tai-khoan/dang-nhap.html';
             header("Location:$url");
             die();
         }
    }
}
