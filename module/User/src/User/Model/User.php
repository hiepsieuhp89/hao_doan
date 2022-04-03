<?php
namespace User\Model;
use Zend\Session\Container;
class User{
    public $id;
    public $fullname;
    public $username;
    public $email;
    public $password;
    public $avatar;
    public $permission;
    public $date;
    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->fullname=(isset($data['fullname']))? $data['fullname']:null;
        $this->username=(isset($data['username']))? $data['username']:null;
        $this->email=(isset($data['email']))? $data['email']:null;
        $this->password=(isset($data['password']))? $data['password']:null;
        $this->avatar=(isset($data['avatar']))? $data['avatar']:null;
        $this->permission=(isset($data['permission']))? $data['permission']:null;
        $this->date=(isset($data['date']))? $data['date']:null;
        
    }
    public function getdata(){
        $data=array();
        $date = date("Y-m-d ");
       
        $data['fullname']=  $this->fullname;
        $data['username']=  $this->username;
        $data['email']=  $this->email;
        $data['password']=  $this->password;
        $data['permission']= $this->permission;
        $data['avatar']=  'default.jpg';
        $data['date']=$date;
        return $data;
    }
    public function getpass(){
        $data=array();
        $data['password']=  $this->password;
        return $data;
    }
    public function checkloginone(){
         $session_user = new Container('user');
         if($session_user->username == null){
             $url=WEB_PATH.'/system';
             header("Location:$url");
             die();
         }
    }
}

