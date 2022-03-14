<?php

namespace Customer\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Customer\Model\Customer;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class CustomerTable extends AbstractTableGateway {

    protected $table = "tbl_customer";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Customer());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }
    public function listacount(){
        $sqlEX=  $this->sql->select();
        $sqlEX->from($this->table);
        $pst=$this->sql->prepareStatementForSqlObject($sqlEX);
        $rs=$pst->execute();
        $data=array();
        foreach ($rs as $ress){
            $data[]=$ress;
        }
        return $data;
    }
     public function changestatus($id, Customer $objuser) {
        $data = $objuser->status();
        $sqlEx = $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {

            return false;
        }
    }
     public function deletecustomer($id) {
        $sqlEx = $this->sql->delete();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }
        // ----------------------- FRONDEND --------------------------------
    public function get_acount_new() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->limit(1);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = $resultset->current();       
        return $data;
    }
    public function acountdetail($id){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        $data=$result->current();
        return $data;
    }
     public function acountdetail_email($email){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('email'=>$email));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        $data=$result->current();
        return $data;
    }
    public function addacount(Customer $obj) {
        $data = $obj->getdata();
        $sqlEx = $this->sql->insert();
        $sqlEx->into($this->table);
        $sqlEx->values($data);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function update_acount($id, Customer $obj){
        $data=$obj->getdata();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('ID'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result != null){
            return TRUE;
        }  else {
            return FALSE;
        }
    }

    public function checkacount($email) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('email' => 'email'));
        $sqlEx->where(array('email' => $email));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if ($data == null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkpass($password) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('password' => 'password'));
        $sqlEx->where(array('password' => $password));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if ($data != null) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function updatepass_user($id, Customer $obj) {
        $data = $obj->datapass();
        $sqlEx = $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();        
        if ($result != null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checklogin($email, $password) {
        $sqlss = $this->sql->select();
        $sqlss->from($this->table);
        $sqlss->where(array("email" => $email, "password" => $password));
        $pst = $this->sql->prepareStatementForSqlObject($sqlss);
        $result = $pst->execute();
        $data = $result->current();
        if ($data != null) {
//            $session_user = new Container('userlogin');
//            $session_user->username = $data['fullname'];
//            $session_user->idus = $data['ID'];
            return true;
        } else {
            return FALSE;
        }
    }

    public function forgetpass($email, $question) {
        $sqlss = $this->sql->select();
        $sqlss->from($this->table);
        $sqlss->where(array("email" => $email, "question_security" => $question));
        $pst = $this->sql->prepareStatementForSqlObject($sqlss);
        $result = $pst->execute();
        $data = $result->current();
        if ($data != null) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function updatepass($email, Customer $obj) {
        $data = $obj->datapass();
        $sqlEx = $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->where(array('email' => $email));
        $sqlEx->set($data);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
