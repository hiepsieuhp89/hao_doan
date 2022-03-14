<?php
namespace Contact\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Contact\Model\Contact;
use Zend\Db\Sql\Select;
use Zend\Session\Container;


class ContactTable extends AbstractTableGateway {

    protected $table = "tbl_contact";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Contact());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }
     public function listcontact(){
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
     public function contactdetail($id){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id'=>$id));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        $data=$result->current();
        return $data;
    }
    public function updatestatus($id, Contact $obj){
        $data=$obj->datastatus();
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

    public function deletecontact($id){
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
    // --------------------------- FRONTEND ------------------------------------
    public function addcontact(Contact $obj){
        $data=$obj->datacontact();
        $sqlEx=  $this->sql->insert();
        $sqlEx->into($this->table);
        $sqlEx->values($data);
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        if($result !=null){
            return TRUE;
        }  else {
            return FALSE;
        }
    } 
   
}