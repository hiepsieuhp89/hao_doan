<?php

namespace Product\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql; //join
use Product\Model\Product;
use Zend\Db\Sql\Select;
use Zend\Session\Container;

class ProductTable extends AbstractTableGateway {

    protected $table = "tbl_products";
    public $sql;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Product());
        $this->initialize();
        $this->sql = new Sql($this->adapter);
    }
    public function listproduct_all(Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $select->join("tbl_categoryproduct", "tbl_categoryproduct.id = tbl_products.cat_id", array("name_cat" => "name"),"left");
        $select->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"),"left");
        $select->order('id DESC');
        $select->where(array(
            'tbl_img.status' => 1,           
            'delete_pro' => 0));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    public function listproduct() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
		$sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"),"left");
        $sqlEx->join("tbl_categoryproduct", "tbl_categoryproduct.id = tbl_products.cat_id", array("name" => "name","parent_id"=>"parent"),"left");
        $sqlEx->where(array('delete_pro' => 0, 'tbl_img.status' => 1,));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }

    public function listproducttrash() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->join("tbl_categoryproduct", "tbl_categoryproduct.id = tbl_products.cat_id", array("name" => "name"));
        $sqlEx->where(array('delete_pro' => 1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }

    public function getproduct_cat($id_cat) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->where(array('cat_id' => $id_cat));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = array();
        foreach ($result as $rs) {
            $data[] = $rs;
        }
        return $data;
    }

    public function getproduct_new() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->limit(1);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }

    public function productdetail($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_categoryproduct", "tbl_categoryproduct.id = tbl_products.cat_id", array("name_cat" => "name"),'left');
        $sqlEx->join("tbluser", "tbluser.id = tbl_products.user_post", array("fullname" => "fullname"),'left');
        $sqlEx->join("tbl_xuatxu", "tbl_xuatxu.id = tbl_products.xuatxu", array("name_xuatxu" => "name"),'left');
        $sqlEx->join("tbl_chatlieu", "tbl_chatlieu.id = tbl_products.chatlieu", array("name_chatlieu" => "name"),'left');
        $sqlEx->join("tbl_manufacture", "tbl_manufacture.id = tbl_products.manufa_id", array("manufacture" => "manu_name"),'left');
        $sqlEx->where(array('tbl_products.id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }

    public function addproduct(Product $objuser) {
        $data = $objuser->dataproduct();
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

    public function checkname($code_id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('code_id' => 'code_id'));
        $sqlEx->where(array('code_id' => $code_id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        if ($data == null) {
            return true;
        } else {
            return false;
        }
    }

    public function updateproduct($id, Product $obj) {
        $data = $obj->dataproduct();
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

    public function update_idcatalog($cat_id, Product $obj) {
        $data = $obj->updatecatalog();
        $sqlEx = $this->sql->update();
        $sqlEx->table($this->table);
        $sqlEx->set($data);
        $sqlEx->where(array('cat_id' => $cat_id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }

    //Trạng thài còn hàng hay hết hàng
    public function statuspro($id, Product $obj) {
        $data = $obj->status_pro();
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

    public function changestatus($id, Product $objuser) {
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

    public function change_showindex($id, Product $objuser) {
        $data = $objuser->show_index();
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

    public function product_trash($id, Product $obj) {
        $data = $obj->trash();
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

    public function deleteall_trash() {
        $sqlEx = $this->sql->delete();
        $sqlEx->from($this->table);
        $sqlEx->where(array('delete_pro' => 1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteproduct($id) {
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

    //xóa sản phẩm khi chọn xóa danh mục
    public function deleteproduct_cat($id) {
        $sqlEx = $this->sql->delete();
        $sqlEx->from($this->table);
        $sqlEx->where(array('cat_id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }

    // --------------------------------- FRONTEND--------------------------------------------
    public function load_product_index($id_cat) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->order('id DESC');
        $sqlEx->limit(8);
        $sqlEx->where(array(
            'cat_id' => $id_cat,
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'show_index' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }
     public function load_product_same($id_cat) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->order('id DESC');
        $sqlEx->limit(8);
        $sqlEx->where(array(
            'cat_id' => $id_cat,
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,           
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function show_product(Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $select->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $select->order('id DESC');
        $select->where(array(
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    public function show_product_cat($id_cat, Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $select->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $select->order('id DESC');
        $select->where->in('cat_id', $id_cat);
        $select->where(array(
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    public function show_product_manufa($id_manu, Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $select->order('id DESC');
        $select->where(array('manufa_id' => $id_manu, 'status' => 1, 'delete_pro' => 0));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    public function show_productdetail($alias) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_categoryproduct", "tbl_categoryproduct.id = tbl_products.cat_id", array("name_cat" => "name", "user_support" => "user", "phone" => "phone"),'left');
        $sqlEx->join("tbl_xuatxu", "tbl_xuatxu.id = tbl_products.xuatxu", array("name_xuatxu" => "name"),'left');
        $sqlEx->join("tbl_chatlieu", "tbl_chatlieu.id = tbl_products.chatlieu", array("name_chatlieu" => "name"),'left');
        $sqlEx->join("tbl_manufacture", "tbl_manufacture.id = tbl_products.manufa_id", array("hangsx" => "manu_name"),'left');
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail", "img" => "img", "medium" => "medium"),'left');
        $sqlEx->where(array('tbl_products.alias' => $alias, 'tbl_img.status' => 1));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $result = $pst->execute();
        $data = $result->current();
        return $data;
    }

    public function product_left() {
        //$rand = new \Zend\Db\Sql\Expression('RAND()');
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->order('id DESC');
        $sqlEx->limit(5);
        $sqlEx->where(array( 
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function product_featured() {
        //$rand = new \Zend\Db\Sql\Expression('RAND()');
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->order('id DESC');
        $sqlEx->limit(8);
        $sqlEx->where(array(
            'product_featured' => 1,
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function product_new($id_cat) {
        //$rand = new \Zend\Db\Sql\Expression('RAND()');
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->order('id DESC');
        $sqlEx->limit(6);
        $sqlEx->where(array(
            'cat_id' => $id_cat,
            'product_new' => 1,
            'status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function product_shoppingcart($id) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->where(array(
            'tbl_img.status' => 1,
            'tbl_products.id' => $id));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function load_productsearch() {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('product_name' => 'product_name', 'alias' => 'alias'));
        $sqlEx->where(array(
            'status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function search_product($key, Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $select->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $select->order('id DESC');
        $select->where(array(
            "product_name LIKE '%$key%'",
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0
        ));
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
	 public function load_productsearch_ajax($key) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
       $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
      $sqlEx->columns(array(          
           'product_name' => 'product_name', 
           'alias' => 'alias',
          'sales'=>'sales',
          'price_sales'=>'price_sales',
           'price'=>'price',
           'cat_id'=>'cat_id'
           ));
        $sqlEx->where(array(
            "product_name LIKE '%$key%'",
             'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }
    //----------------------bộ lọc----------------------
    public function countkhoanggia($id_khoanggia) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('khoanggia' => 'khoanggia'));
        $sqlEx->where(array(
            'khoanggia' => $id_khoanggia,
            'status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function count_brand($id_brand) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('manufa_id' => 'manufa_id'));
        $sqlEx->where(array(
            'manufa_id' => $id_brand,
            'status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function count_xuatxu($id_xuatxu) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('xuatxu' => 'xuatxu'));
        $sqlEx->where(array(
            'xuatxu' => $id_xuatxu,
            'status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function count_chatlieu($id_chatlieu) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->columns(array('chatlieu' => 'chatlieu'));
        //$sqlEx->where();
        $sqlEx->where(array(
            'chatlieu' => $id_chatlieu,
            'status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

    public function list_product_filter($where) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
       $sqlEx->order('id DESC');
        $sqlEx->where($where);
        $sqlEx->where(array(
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));        
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }
     public function list_product_filter_cat($id_cat,$where) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->order('id DESC');
        $sqlEx->where($where);
        $sqlEx->where(array(
            'cat_id'=>$id_cat,
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }
     public function list_product_filter_search($key,$where) {
        $sqlEx = $this->sql->select();
        $sqlEx->from($this->table);
        $sqlEx->join("tbl_img", "tbl_img.id_product = tbl_products.id", array("thumbnail" => "thumbnail"));
        $sqlEx->order('id DESC');        
        $sqlEx->where(array(
            "product_name LIKE '%$key%'",
            'tbl_img.status' => 1,
            'tbl_products.status' => 1,
            'delete_pro' => 0));
        $sqlEx->where($where);
        $pst = $this->sql->prepareStatementForSqlObject($sqlEx);
        $resultset = $pst->execute();
        $data = array();
        foreach ($resultset as $result) {
            $data[] = $result;
        }
        return $data;
    }

}
