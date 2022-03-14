<?php

namespace Product\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Product\Model\Productcategory;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class ProductTablecategory extends AbstractTableGateway {

    protected $table = "tbl_categoryproduct";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Productcategory());
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
    
    public function load_parent_admin($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('parent'=>$id));
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
    public function categorydetail($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
    
    public function addcategory(Productcategory $objuser) {      
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
   
    public function updatesort($id, Productcategory $objuser) {
        $data = $objuser->datasort();
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
	
	public function updatesort_menu($id, Productcategory $objuser) {
        $data = $objuser->datasort_menu();
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
	
    public function updatcategory($id, Productcategory $objuser) {
        $data = $objuser->datacat();
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
      public function updateparent($id, Productcategory $objuser) {
        $data = $objuser->dataparent();
        $sqlEx = $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('parent' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {

            return false;
        }
    }
     public function showindex($id, Productcategory $objuser) {
        $data = $objuser->datashowindex();
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

     public function changestatus($id, Productcategory $objuser) {
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

   
    // ------------------------------ FRONDEND -------------------------
    private function getsubmenu($id, $list) {
        $data = $this->loadparent($id);
       $l = "";
        foreach ($data as $key => $vl) {
            $id = $vl['id'];
            $l .= $l . "," . $id;
            $this->getsubmenu($id, $list);
        }
        return $list . $l;
    }

//Tam thoi fix bang 58
    public function getallMenu($id) {
        $f_id=$id;
        $data = $this->loadparent($f_id);
        
        $list = "";
        foreach ($data as $key => $vl) {
            
            $id = $vl['id'];         
            $list .= $list . "," . $id;           
            //$list = $list . "," . $this->getsubmenu($id, $list); //d? nhu n�y n?u s? lu?ng danh m?c con >9 s? b? die
            $list = $this->getsubmenu($id, $list);
            
        }       
        $arr_tmp = explode(",", $list);        
        $arr_tmp_2 = array_unique($arr_tmp);       
       $arr_tmp_2[0] = $f_id;       
       $arr=array();
        $index=0;
        foreach($arr_tmp_2 as $v){
            $arr[$index]=$v;
            $index++;
           
        }
       
        return $arr;
    }
    public function loadparent($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('parent' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        return $result;
    }
    
    public function load_category_index(){
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);    
        $sqlEx->order('sort ASC');
        $sqlEx->where(array('show_index'=>1, 'parent'=>0,'status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }

    public function load_category() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('sort_menu ASC');
        $sqlEx->where(array( 'status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
    public function load_parent($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('parent'=>$id, 'status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
    
    public function load_cat_featured() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->limit(3);
        $sqlEx->where(array('cat_featured'=>1, 'status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
    
    public function load_cat_new() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->limit(3);
        $sqlEx->where(array('cat_new'=>1, 'status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
    public function categorydetail_alias($alias) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('alias' => $alias));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
}
