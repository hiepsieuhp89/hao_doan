<?php

namespace Invoice\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Invoice\Model\Oder;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class OderTable extends AbstractTableGateway {

    protected $table = "tbl_order";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Oder());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }

   public function listorder() {       
       $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table); 
        $sqlEx->order('id DESC');
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        $data=array();
        foreach ($result as $resultset){
            $data[]=$resultset;
        }
        return $data;
    }
    public function view_order($id){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id'=>$id));            
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $resulr=$pst->execute();
        $data=$resulr->current();
        return $data;
    }
    
    public function getoder_new(){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('id'=>'id'));
        $sqlEx->order('id DESC');
        $sqlEx->limit(1);
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $resulr=$pst->execute();
        $data=$resulr->current();
        return $data;
    }
    public function update_status($id, Oder $obj){
        $data=$obj->status();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id'=>$id));
         $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return TRUE;
        } else {
            return FALSE;
        }       
    }
    public function delete_oder($id){
        $sqlEx=  $this->sql->delete();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id'=>$id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return TRUE;
        } else {
            return FALSE;
        }       
    }
    
    
    //-------------------------------------- FRONDEND ------------------------------------
    
    public function addoder(Oder $obj) {
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
     public function getoder_user($id_user, Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);      
        $select->order('id DESC');
        $select->where(array('id_customer'=>$id_user));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
   
     public function get_code_oder(){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('code_oder'=>'code_oder'));
        $sqlEx->order('code_oder DESC');
        $sqlEx->limit(1);
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $resulr=$pst->execute();
        $data=$resulr->current();
        return $data;
    }
}
