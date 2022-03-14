<?php

namespace User\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use User\Model\User;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class UserTable extends AbstractTableGateway {

    protected $table = "tbluser";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new User());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }

    public function listuser() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }

    public function detailuser($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
    public function getusername($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
         $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
    public function adduser(User $objuser) {
       // $check = $this->checkuser($objuser->username);
       // if ($check) {
            $data = $objuser->getdata();
            $sqlEx = $this->sql->insert();
            $sqlEx->into($this->table);
            $sqlEx->values($data);
            $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
            $result = $pst->execute();
            if ($result != null) {
                return true;
            } else {
                return false;
            }
       // }
    }

    public function checkuser($username) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('username' => 'username'));
        $sqlEx->where(array('username' => $username));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if($data == null){
            return true;
        }  else {
            return false;
        }
        /*$data = $result->count();
        if ($data > 0) {            
            return false;
        }
            return true;*/
    }
    public function checkpass($password){
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('password' => 'password'));
        $sqlEx->where(array('password' => $password));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if($data != null){
            return true;
        }  else {
            return false;
        }
    }

    public function updateuser($id, User $objuser) {
        $data = $objuser->getdata();
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

    public function changerpass($id, User $objuser) {
        $data = $objuser->getpass();
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

    public function deleteuser($id) {
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

    public function checklogin($username, $password) {
        $sqlss = $this->sql->select();
        $sqlss->from($this->table);
        $sqlss->where(array("username" => $username, "password" => $password));
        $pst = $this->sql->prepareStatementForSqlObject($sqlss);
        $result = $pst->execute();
        $data = $result->current();
        if ($data != null) {
            $session_user = new Container('user');
            $session_user->username = $username;
            $session_user->idus = $data['id'];
            $session_user->permission=$data['permission'];
            $session_user->fullname=$data['fullname'];
            return true;
        } else {
            return FALSE;
        }
    }
    
}
