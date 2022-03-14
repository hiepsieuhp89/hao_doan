<?php

namespace News\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use News\Model\News;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class NewsTable extends AbstractTableGateway {

    protected $table = "tbl_news";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new News());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }

    public function listnews() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->join("tbl_categorynews","tbl_categorynews.id = tbl_news.news_catid",array("name"=>"name"));
        $sqlEx->join("tbluser","tbluser.id = tbl_news.id_user",array("fullname"=>"fullname"));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }
     
    
    public function detailnews($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
    
    public function addnews(News $objuser) {      
            $data = $objuser->datanews();
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
        $sqlEx->columns(array('news_title' => 'news_title'));
        $sqlEx->where(array('news_title' => $name));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if($data == null){
            return true;
        }  else {
            return false;
        }        
    }
    
    public function updatenews($id, News $obj) {
        $data = $obj->datanews();
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

    public function changestatus($id, News $objuser) {
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

    public function deletenews($id) {
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
    public function deletenews_cat($id_cat) {
        $sqlEx = $this->sql->delete();
        $sqlEx->from($this->table);
        $sqlEx->where(array('news_catid' => $id_cat));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }

    // ---------------------------------- FRONDEND -------------------------------------
    public function load_news_index(){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');      
        $sqlEx->where(array('news_status'=>1 ));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        $data = array();
        foreach ($result as $rs){
            $data[]=$rs;
        }
        return $data;
    }
     public function load(){
        $sqlEx=  $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');      
        $sqlEx->where(array('news_status'=>1 ));
        $pst=  $this->sql->prepareStatementForSqlObject($sqlEx);
        $result=$pst->execute();
        $data = array();
        foreach ($result as $rs){
            $data[]=$rs;
        }
        return $data;
    }
    public function load_news( Select $select = null) {       
        if (null === $select)
        $select = new Select();
        $select->from($this->table);       
        $select->order('id DESC');
        $select->where(array('news_status'=>1));        
        $select->join('tbl_categorynews','tbl_news.news_catid = tbl_categorynews.id', array("alias"=>"alias"));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
     public function views_news($alias) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbluser","tbluser.id = tbl_news.id_user",array("fullname"=>"fullname"));
        $sqlEx->where(array('news_alias' => $alias));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }
    
      public function news_random(){
        $rand = new \Zend\Db\Sql\Expression('RAND()');
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);        
        $sqlEx->order($rand);
        $sqlEx->limit(5);
        $sqlEx->where(array('news_status'=>1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }
    
    public function load_news_cat($id_cat, Select $select = null) {       
        if (null === $select)
        $select = new Select();
        $select->from($this->table);       
        $select->order('id DESC');
        $select->where(array('news_status'=>1, 'news_catid'=>$id_cat));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
}
