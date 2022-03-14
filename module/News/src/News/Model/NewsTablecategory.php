<?php

namespace News\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use News\Model\Newscategory;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class NewsTablecategory extends AbstractTableGateway {

    protected $table = "tbl_categorynews";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Newscategory());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }

    public function listcategory() {
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
    public function getparent_name($parent){
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $parent));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data; 
    }

    public function listcatparent() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->where(array('status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
    public function detailcategory($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
    
    public function addcategory(Newscategory $objuser) {      
            $data = $objuser->datacat();
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
    
    public function updatecatalog($id, Newscategory $obj) {
        $data = $obj->datacat();
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

    public function changestatus($id, Newscategory $objuser) {
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

    public function deletecategory($id) {
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

    
    // ----------------------------- Frond End ---------------------
     public function load_cat() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);        
        $sqlEx->where(array('status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
     public function detail_cat($alias) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('alias' => $alias));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
    public function detail_cat_id($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('alias'=>'alias'));
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
}
