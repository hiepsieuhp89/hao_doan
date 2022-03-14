<?php
namespace Setting\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Setting\Model\Setting;
use Zend\Db\Sql\Select;
use Zend\Session\Container;


class SettingTable extends AbstractTableGateway {

    protected $table = "tbl_setting";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Setting());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }
     public function datasetting() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data=$resultset->current();
        //$data[]=$resultset->current();          
        return $data;
    }
    public function editsetting($id, Setting $obj){
        $data=$obj->datasetting();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result !=null){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    public function editseo($id, Setting $obj){
        $data=$obj->dataseo();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result !=null){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
     public function editabout($id, Setting $obj){
        $data=$obj->dataabout();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result !=null){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
     public function editsociu($id, Setting $obj){
         $data=$obj->datasociu();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result !=null){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
     public function editemail($id, Setting $obj){
         $data=$obj->dataemail();
        $sqlEx=  $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result !=null){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
}