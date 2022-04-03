<?php

namespace Contact\Model;

class Contact {

    public $id;
    public $fullname;
    public $email;
    public $phone;
    public $address;
    public $title;
    public $content;
    public $date;
    public $status;
    

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->fullname = (isset($data['fullname'])) ? $data['fullname'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->phone = (isset($data['phone'])) ? $data['phone'] : null;
        $this->address = (isset($data['address'])) ? $data['address'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
        $this->date_creaded = (isset($data['date'])) ? $data['date'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
    }
    public function datacontact(){        
        $data=array();
        $date = date("Y-m-d ");
        $data['fullname']=  $this->fullname;
        $data['email']= $this->email;
        $data['phone']=$this->phone;        
        $data['address']=  $this->address;
        $data['content']=$this->content;
        $data['date']=  $date;
        return $data;
    }
    public function datastatus(){
        $data=array();
        $data['status']=  $this->status;
        return $data;
    }
}
