<?php

namespace Product\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Product\Model\Chatlieu;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class ChatlieuTable extends AbstractTableGateway {

    protected $table = "tbl_chatlieu";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Chatlieu());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }
     public function listchatlieu() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $resultset){
            $data[]=$resultset;
        }
        return $data;
    }
  
     public function getimg_new() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->limit(1);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();        
        return $data;
    }
    public function chatlieudetail($id){
       $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id'=>$id));        
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data; 
    }

    

    public function addchatlieu(Chatlieu $objuser) {      
            $data = $objuser->data();
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
      
    }
 public function updatechatlieu($id, Chatlieu $objuser) {
        $data = $objuser->data();
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
     public function checkname($name) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('name' => 'name'));
        $sqlEx->where(array('name' => $name));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if($data == null){
            return true;
        }  else {
            return false;
        }        
    }
   
public function changestatus($id, Chatlieu $objuser) {
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
    public function delete_chatlieu($id) {
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
  
        //-------------------------- Frond End
    public function listchatlieu_index() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->where(array('status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $resultset){
            $data[]=$resultset;
        }
        return $data;
    }
}

