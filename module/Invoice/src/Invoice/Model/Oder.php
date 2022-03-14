<?php

namespace Invoice\Model;

class Oder {
    public $id;
    public $id_customer;
    public $code_oder;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $status_order;
    public $city;
    public $total_money;    
    public $type; 
    public $content; 
    public $date;
    
   
 
    function exchangeArray($data) 
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->id_customer = (isset($data['id_customer'])) ? $data['id_customer'] : null;
        $this->code_oder = (isset($data['code_oder'])) ? $data['code_oder'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->phone = (isset($data['phone'])) ? $data['phone'] : null;
        $this->address = (isset($data['address'])) ? $data['address'] : null;
        $this->status_order = (isset($data['status_order'])) ? $data['status_order'] : null;
        $this->city = (isset($data['city'])) ? $data['city'] : null;
        $this->total_money = (isset($data['total_money'])) ? $data['total_money'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;        
    }
     public function getdata() {
        $data = array();
        $date = date('Y-m-d');
        $data['id_customer'] = $this->id_customer;
        $data['code_oder'] = $this->code_oder;
        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['phone'] = $this->phone;
        $data['address'] = $this->address;
        $data['status_order'] = 0;         
        $data['total_money'] = $this->total_money; 
        $data['content'] = $this->content; 
        $data['date'] = $date;  
        return $data;
    }
    public function status(){
       $data = array();  
       $data['status_order'] = $this->status_order;
       return $data;
    }
}